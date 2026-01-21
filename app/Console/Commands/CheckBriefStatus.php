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
        $this->info('Iniciando verificación de estados de Briefs...');

        $briefs = Brief::where('estado', 'pendiente')
                       ->whereNotNull('id_cliente')
                       ->get();

        $count = 0;

        foreach ($briefs as $brief) {
            // 1. Verificar si ya hay respuestas en Google Forms
            try {
                $responsesList = $googleFormsService->getFormResponses($brief->google_form_id);
                $responses = $responsesList ? $responsesList->getResponses() : [];

                if (!empty($responses)) {
                    $brief->update(['estado' => 'recibido']);
                    $this->info("✅ Brief '{$brief->titulo}' (ID: {$brief->id}) marcado como RECIBIDO (tiene respuestas).");
                    continue; // Pasamos al siguiente, ya no se evalúa recordatorio
                }
            } catch (\Exception $e) {
                $this->error("❌ Error verificando respuestas para Brief ID {$brief->id}: " . $e->getMessage());
                Log::error("Brief Command Error: " . $e->getMessage());
                continue; // Si falla la API, saltamos para evitar enviar recordatorios erróneos
            }

            // 2. Verificar si corresponde enviar recordatorio
            // Lógica: Si han pasado 5 días desde el envío inicial
            if ($brief->fecha_envio) {
                $diasDesdeEnvio = $brief->fecha_envio->diffInDays(now());

                if ($diasDesdeEnvio >= 5) {
                    // Verificamos frecuencia de recordatorio:
                    // Enviamos si NUNCA se ha enviado, o si pasaron 5 días desde el último (recordatorio recurrente)
                    $diasDesdeUltimo = $brief->fecha_ultimo_recordatorio 
                        ? $brief->fecha_ultimo_recordatorio->diffInDays(now()) 
                        : 999;

                    if ($diasDesdeUltimo >= 5) {
                        if ($brief->cliente && $brief->cliente->user) {
                            try {
                                // 1. Enviar Correo
                                Mail::to($brief->cliente->user->email)->send(new \App\Mail\BriefReminder($brief));
                                
                                // 2. Enviar Notificación de Sistema (Tabla Personalizada)
                                Notificacion::create([
                                    'user_id' => $brief->cliente->user->id,
                                    'tipo' => 'alerta',
                                    'titulo' => 'Recordatorio de Brief',
                                    'mensaje' => "No olvides completar el formulario: {$brief->titulo}. Haz clic para abrirlo.",
                                    'url' => $brief->form_url,
                                    'leida' => false,
                                ]);
                                
                                $brief->update(['fecha_ultimo_recordatorio' => now()]);
                                $this->info("🔔 Recordatorio enviado (Email + Sistema) para '{$brief->titulo}' al cliente {$brief->cliente->nombre}.");
                                $count++;
                            } catch (\Exception $e) {
                                $this->error("❌ Error enviando recordatorio a cliente {$brief->cliente->id_cliente}: " . $e->getMessage());
                            }
                        } else {
                            $this->warn("⚠️ Brief ID {$brief->id} debe tener recordatorio pero el cliente no tiene usuario/email válido.");
                        }
                    }
                }
            }
        }

        $this->info("Proceso finalizado. Recordatorios enviados: {$count}");
    }
}
