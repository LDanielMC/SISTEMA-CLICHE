<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Brief;
use App\Models\Notificacion;

class FixMissingNotifications extends Command
{
    protected $signature = 'notifications:fix';
    protected $description = 'Genera notificaciones faltantes para briefs pendientes';

    public function handle()
    {
        $this->info('Buscando briefs pendientes sin notificación...');

        $briefs = Brief::where('estado', 'pendiente')
                       ->whereNotNull('id_cliente')
                       ->with('cliente.user')
                       ->get();

        $count = 0;

        foreach ($briefs as $brief) {
            if (!$brief->cliente || !$brief->cliente->user) {
                continue;
            }

            $userId = $brief->cliente->user->id;
            $tituloNotificacion = 'Nuevo Formulario Asignado';
            
            // Verificar si ya existe
            $exists = Notificacion::where('user_id', $userId)
                                  ->where('tipo', 'alerta')
                                  ->where('mensaje', 'LIKE', "%{$brief->titulo}%")
                                  ->exists();

            if (!$exists) {
                Notificacion::create([
                    'user_id' => $userId,
                    'tipo' => 'alerta',
                    'titulo' => $tituloNotificacion,
                    'mensaje' => "Se te ha asignado: {$brief->titulo}. Por favor respóndelo pronto.",
                    'url' => $brief->form_url,
                    'leida' => false,
                ]);
                $this->info(" + Notificación creada para: {$brief->titulo} (Usuario ID: {$userId})");
                $count++;
            }
        }

        $this->info("Proceso terminado. Notificaciones creadas: {$count}");
    }
}
