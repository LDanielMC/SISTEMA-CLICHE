<?php

namespace App\Jobs;

use App\Models\EventoRecordatorio;
use App\Models\Notificacion;
use App\Mail\RecordatorioEvento;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class EnviarRecordatoriosEventos implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Obtener recordatorios pendientes
        $recordatorios = EventoRecordatorio::with(['evento.participantes', 'evento.cliente', 'evento.creador'])
            ->pendientes()
            ->paraEnviar()
            ->get();

        foreach ($recordatorios as $recordatorio) {
            $evento = $recordatorio->evento;
            
            // Calcular si es momento de enviar el recordatorio
            $fechaHoraEvento = Carbon::parse($evento->fecha->format('Y-m-d') . ' ' . $evento->hora_inicio);
            $fechaEnvio = $fechaHoraEvento->copy()->subMinutes($recordatorio->minutos_antes);
            
            // Si ya es hora de enviar (con margen de 5 minutos)
            if (now()->greaterThanOrEqualTo($fechaEnvio->subMinutes(5))) {
                
                // Enviar según tipo de notificación
                if (in_array($recordatorio->tipo_notificacion, ['correo', 'ambos'])) {
                    $this->enviarCorreos($recordatorio);
                }
                
                if (in_array($recordatorio->tipo_notificacion, ['sistema', 'ambos'])) {
                    $this->crearNotificacionesSistema($recordatorio);
                }
                
                // Marcar como enviado
                $recordatorio->update([
                    'enviado' => true,
                    'fecha_envio' => now(),
                ]);
            }
        }
    }

    private function enviarCorreos(EventoRecordatorio $recordatorio)
    {
        $evento = $recordatorio->evento;
        
        // Enviar al creador del evento
        try {
            Mail::to($evento->creador->email)->send(new RecordatorioEvento($evento, $recordatorio));
        } catch (\Exception $e) {
            \Log::error('Error al enviar recordatorio al creador: ' . $e->getMessage());
        }
        
        // Enviar a participantes
        foreach ($evento->participantes as $participante) {
            try {
                Mail::to($participante->correo)->send(new RecordatorioEvento($evento, $recordatorio));
            } catch (\Exception $e) {
                \Log::error('Error al enviar recordatorio a participante: ' . $e->getMessage());
            }
        }
    }

    private function crearNotificacionesSistema(EventoRecordatorio $recordatorio)
    {
        $evento = $recordatorio->evento;
        
        $tiempoTexto = $this->formatearTiempo($recordatorio->minutos_antes);
        $fechaHora = $evento->fecha->format('d/m/Y') . ' a las ' . date('H:i', strtotime($evento->hora_inicio));
        
        // Notificar al creador
        Notificacion::create([
            'user_id' => $evento->creado_por,
            'asignacion_tarea_id' => null,
            'tipo' => 'recordatorio_evento',
            'titulo' => '🔔 Recordatorio: ' . $evento->titulo,
            'mensaje' => "Tu evento '{$evento->titulo}' es {$tiempoTexto}. Fecha: {$fechaHora}",
        ]);
        
        // Notificar a participantes que sean empleados
        foreach ($evento->participantes as $participante) {
            if ($participante->tipo === 'empleado' && $participante->empleado && $participante->empleado->id_usuario) {
                Notificacion::create([
                    'user_id' => $participante->empleado->id_usuario,
                    'asignacion_tarea_id' => null,
                    'tipo' => 'recordatorio_evento',
                    'titulo' => '🔔 Recordatorio: ' . $evento->titulo,
                    'mensaje' => "Tienes el evento '{$evento->titulo}' {$tiempoTexto}. Fecha: {$fechaHora}",
                ]);
            }
        }
    }

    private function formatearTiempo($minutos)
    {
        if ($minutos < 60) {
            return "en {$minutos} minutos";
        } elseif ($minutos < 1440) {
            $horas = floor($minutos / 60);
            return "en {$horas} " . ($horas == 1 ? 'hora' : 'horas');
        } else {
            $dias = floor($minutos / 1440);
            return "en {$dias} " . ($dias == 1 ? 'día' : 'días');
        }
    }
}

