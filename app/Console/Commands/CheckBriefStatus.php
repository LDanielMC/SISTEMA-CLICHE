<?php

namespace App\Console\Commands;

use App\Models\Brief;
use App\Models\Notificacion;
use App\Services\GoogleFormsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckBriefStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'briefs:check-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica si los briefs han sido respondidos y envía recordatorios si han pasado 5 días.';

    /**
     * Execute the console command.
     * 
     * Verifica el estado de los briefs pendientes, detecta respuestas en Google Forms
     * mediante coincidencia de emails, y envía recordatorios automáticos cada 5 días.
     *
     * @param GoogleFormsService $googleFormsService Servicio inyectado para interactuar con Google Forms API.
     *                                                Permite obtener las respuestas de los formularios y verificar
     *                                                si los clientes han completado sus briefs asignados.
     * @return void
     */
    public function handle(GoogleFormsService $googleFormsService)
    {
        $this->info('Iniciando verificación de estados de Briefs (Relación Muchos a Muchos)...');

        // Obtener briefs que tengan al menos un cliente asignado con estado 'pendiente'
        // Se utilizan whereHas para filtrar por la relación de la tabla pivot
        $briefs = Brief::whereHas('clientes', function ($q) {
            $q->where('brief_cliente.estado', 'pendiente');
        })->with(['clientes' => function ($q) {
            // Cargar los campos pivote necesarios para la lógica posterior
            $q->withPivot('estado', 'fecha_envio', 'fecha_ultimo_recordatorio', 'google_response_id');
        }])->get();

        // ... procesamiento de los briefs obtenidos

        $count = 0;

        foreach ($briefs as $brief) {
            $responses = [];
            
            // 1. Obtener respuestas de Google Forms UNA VEZ por brief
            try {
                $responsesList = $googleFormsService->getFormResponses($brief->google_form_id);
                $responses = $responsesList ? $responsesList->getResponses() : [];
            } catch (\Exception $e) {
                $this->error("❌ Error obteniendo respuestas para Brief ID {$brief->id}: " . $e->getMessage());
                Log::error("Brief Command Error (ID {$brief->id}): " . $e->getMessage());
                // Continuamos, pero asumimos que no hay respuestas nuevas si falla la API
                $responses = [];
            }

            // 2. Iterar sobre cada cliente asignado pendiente
            foreach ($brief->clientes->where('pivot.estado', 'pendiente') as $cliente) {
                $pivot = $cliente->pivot;
                $completed = false;
                
                // Recalcular otros emails para ESTE cliente
                 $otherClientsEmails = $brief->clientes
                    ->where('id_cliente', '!=', $cliente->id_cliente)
                    ->pluck('user.email')
                    ->filter()
                    ->map(fn($e) => strtolower(trim($e)))
                    ->toArray();

                // A) Verificar si este cliente específico respondió
                if (!empty($responses)) {
                    foreach ($responses as $response) {
                        $responseId = $response->getResponseId();
                        $submissionTime = \Carbon\Carbon::parse($response->getCreateTime());
                        $fechaEnvio = $pivot->fecha_envio ? \Carbon\Carbon::parse($pivot->fecha_envio) : null;
                        
                        // 1. Vinculación Manual (Prioridad Absoluta)
                        if (isset($pivot->google_response_id) && $pivot->google_response_id === $responseId) {
                             $completed = true;
                             break;
                        } 
                        
                        // Si ya tiene vinculación manual a OTRO ID, este response no cuenta (salvo que sea el linked, arriba checkeado)
                        if (isset($pivot->google_response_id) && $pivot->google_response_id) {
                            continue;
                        }

                        // 2. Verificar que la respuesta sea POSTERIOR a la fecha de envío
                        // Esto permite solicitar nuevas respuestas y que solo cuenten las nuevas
                        if ($fechaEnvio && $submissionTime->lessThanOrEqualTo($fechaEnvio)) {
                            continue; // Respuesta anterior al envío, no cuenta
                        }

                        // 3. Exclusión Inteligente
                        $respondentEmail = strtolower(trim($response->getRespondentEmail()));
                        
                        // Si el email es de OTRO cliente asignado, lo saltamos
                        if ($respondentEmail && in_array($respondentEmail, $otherClientsEmails)) {
                            continue;
                        }

                        // 3. Email en Cuerpo (con Exclusión)
                        $foundEmailInBody = false;
                        
                        if ($cliente->user && $cliente->user->email) {
                            $targetEmail = strtolower(trim($cliente->user->email));
                            $answers = $response->getAnswers();
                            if ($answers) {
                                foreach ($answers as $answer) {
                                    $textAnswers = $answer->getTextAnswers();
                                    if ($textAnswers && $textAnswers->getAnswers()) {
                                        foreach ($textAnswers->getAnswers() as $textAnswer) {
                                            $val = strtolower(trim($textAnswer->getValue()));
                                            
                                            if ($val === $targetEmail) {
                                                $foundEmailInBody = true;
                                            }
                                            // Si encontramos email de OTRO cliente en el cuerpo, saltamos este response
                                            if (in_array($val, $otherClientsEmails)) {
                                                continue 3; // Salir respuestas internas y pasar al siguiente response
                                            }
                                        }
                                    }
                                }
                            }
                            
                            if (($respondentEmail === $targetEmail) || $foundEmailInBody) {
                                $completed = true;
                                // Auto-vincular la respuesta detectada
                                $brief->clientes()->updateExistingPivot($cliente->id_cliente, [
                                    'google_response_id' => $responseId,
                                    'estado' => 'recibido'
                                ]);
                                $this->info("✅ Respuesta detectada y vinculada para {$cliente->nombre} en '{$brief->titulo}'");
                                break;
                            }
                        }

                        // 4. Fecha (Fallback) - DESHABILITADO
                        // El fallback por fecha causaba falsos positivos al vincular respuestas antiguas
                        // Ahora solo se vincula si hay coincidencia por email (metadata o cuerpo)
                        // Si el cliente respondió sin email identificable, debe vincularse manualmente
                    }
                }

                if ($completed) continue; // Si ya completó, no enviamos recordatorio

                // B) Verificar Recordatorio (5 días)
                if ($pivot->fecha_envio) {
                    $fechaEnvio = \Carbon\Carbon::parse($pivot->fecha_envio);
                    $diasDesdeEnvio = $fechaEnvio->diffInDays(now());

                    if ($diasDesdeEnvio >= 5) {
                        $fechaUltimo = $pivot->fecha_ultimo_recordatorio ? \Carbon\Carbon::parse($pivot->fecha_ultimo_recordatorio) : null;
                        
                        $diasDesdeUltimo = $fechaUltimo 
                            ? $fechaUltimo->diffInDays(now()) 
                            : 999;

                        if ($diasDesdeUltimo >= 5) {
                            if ($cliente->user) {
                                try {
                                    // 1. Enviar Correo
                                    Mail::to($cliente->user->email)->send(new \App\Mail\BriefReminder($brief, $cliente));
                                    
                                    // 2. Notificación Sistema
                                    Notificacion::create([
                                        'user_id' => $cliente->user->id,
                                        'tipo' => 'alerta',
                                        'titulo' => 'Recordatorio de Brief',
                                        'mensaje' => "No olvides completar: {$brief->titulo}. Haz clic aquí.",
                                        'url' => $brief->form_url,
                                        'leida' => false,
                                    ]);
                                    
                                    // Actualizar fecha último recordatorio en PIVOT
                                    $brief->clientes()->updateExistingPivot($cliente->id_cliente, ['fecha_ultimo_recordatorio' => now()]);
                                    
                                    $this->info("🔔 Recordatorio enviado a {$cliente->nombre} para '{$brief->titulo}'.");
                                    $count++;
                                } catch (\Exception $e) {
                                    $this->error("❌ Error enviando recordatorio a {$cliente->nombre}: " . $e->getMessage());
                                }
                            } else {
                                $this->warn("⚠️ Cliente {$cliente->nombre} sin usuario para recordatorio.");
                            }
                        }
                    }
                }
            }
        }

        $this->info("Proceso finalizado. Recordatorios enviados: {$count}");
    }
}
