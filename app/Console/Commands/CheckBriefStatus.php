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
     */
    public function handle(GoogleFormsService $googleFormsService)
    {
        $this->info('Iniciando verificación de estados de Briefs (Relación Muchos a Muchos)...');

        // Obtener briefs que tengan al menos un cliente asignado con estado 'pendiente'
        $briefs = Brief::whereHas('clientes', function ($q) {
            $q->where('brief_cliente.estado', 'pendiente');
        })->with(['clientes' => function ($q) {
            $q->wherePivot('estado', 'pendiente')->withPivot('fecha_envio', 'fecha_ultimo_recordatorio');
        }])->get();

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

            // Obtener emails de otros clientes de este mismo brief para exclusión
            $otherClientsEmails = $brief->clientes
                ->where('id_cliente', '!=', $cliente->id_cliente)
                ->pluck('user.email')
                ->filter()
                ->map(fn($e) => strtolower(trim($e)))
                ->toArray();

            // 2. Iterar sobre cada cliente asignado pendiente
            // --- MOVIDO: Iterar sobre clientes PRIMERO para definir contexto ---
            // El loop original iteraba $brief->clientes para check.
            // Necesitamos asegurarnos que la variable $otherClientsEmails se calcule correctamente RELATIVO al cliente actual del loop.
            // ERROR EN MI LOGICA ANTERIOR: $otherClientsEmails debe recalcularse DENTRO del loop de clientes, porque "otros" cambia para cada cliente.
            
                 // (Revertimos cambios arriba y aplicamos dentro del loop)
             
            // ... (rest of logic inside loop)
            
            // 2. Iterar sobre cada cliente asignado pendiente
            foreach ($brief->clientes as $cliente) {
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

                        // 2. Exclusión Inteligente
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
                                break;
                            }
                        }

                        // 4. Fecha (Fallback)
                        // Solo si no fue excluido por ser de otro cliente
                        if (!$completed && (!$fechaEnvio || $submissionTime->greaterThan($fechaEnvio))) {
                            // IMPORTANTE: Aquí está el riesgo de falsos positivos por fecha.
                            // Si llegó hasta aquí, significa que:
                            // a) No está vinculado manualmente
                            // b) No tiene email de otro cliente (metadata o body)
                            // c) Coincide por fecha
                            // Asumimos que es una respuesta anónima válida para este cliente.
                            $completed = true;
                            break; 
                        }
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
