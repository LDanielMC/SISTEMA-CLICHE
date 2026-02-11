<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\RecordatorioPublicaciones;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EnviarRecordatoriosPublicaciones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publicaciones:recordatorios {--simulate : Simular envío sin mandar correos reales}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar recordatorios de publicaciones pendientes para hoy y mañana al administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $simulate = $this->option('simulate');
        $hoy = Carbon::today();
        $manana = Carbon::tomorrow();
        
        if ($simulate) {
            $this->info('=== MODO SIMULACIÓN ===');
        }
        
        // Publicaciones pendientes para HOY
        $publicacionesHoy = DB::table('publicaciones')
            ->join('clientes', 'publicaciones.cliente_id', '=', 'clientes.id_cliente')
            ->join('plataformas', 'publicaciones.plataforma_id', '=', 'plataformas.id')
            ->join('formatos', 'publicaciones.formato_id', '=', 'formatos.id')
            ->where('publicaciones.fecha', $hoy)
            ->where('publicaciones.estatus', 'Pendiente')
            ->select(
                'publicaciones.*',
                'clientes.empresa as cliente_nombre',
                'plataformas.nombre as plataforma_nombre',
                'formatos.nombre as formato_nombre'
            )
            ->get();
        
        // Publicaciones pendientes para MAÑANA
        $publicacionesManana = DB::table('publicaciones')
            ->join('clientes', 'publicaciones.cliente_id', '=', 'clientes.id_cliente')
            ->join('plataformas', 'publicaciones.plataforma_id', '=', 'plataformas.id')
            ->join('formatos', 'publicaciones.formato_id', '=', 'formatos.id')
            ->where('publicaciones.fecha', $manana)
            ->where('publicaciones.estatus', 'Pendiente')
            ->select(
                'publicaciones.*',
                'clientes.empresa as cliente_nombre',
                'plataformas.nombre as plataforma_nombre',
                'formatos.nombre as formato_nombre'
            )
            ->get();
        
        // Publicaciones ATRASADAS (fecha pasada y aún pendientes)
        $publicacionesAtrasadas = DB::table('publicaciones')
            ->join('clientes', 'publicaciones.cliente_id', '=', 'clientes.id_cliente')
            ->join('plataformas', 'publicaciones.plataforma_id', '=', 'plataformas.id')
            ->join('formatos', 'publicaciones.formato_id', '=', 'formatos.id')
            ->where('publicaciones.fecha', '<', $hoy)
            ->where('publicaciones.estatus', 'Pendiente')
            ->select(
                'publicaciones.*',
                'clientes.empresa as cliente_nombre',
                'plataformas.nombre as plataforma_nombre',
                'formatos.nombre as formato_nombre'
            )
            ->orderBy('publicaciones.fecha', 'asc')
            ->get();
        
        $totalPublicaciones = $publicacionesHoy->count() + $publicacionesManana->count() + $publicacionesAtrasadas->count();
        
        if ($totalPublicaciones > 0) {
            // Obtener email del admin
            $admin = User::where('rol', 'admin')->first();
            
            if (!$admin) {
                $this->error('No se encontró un usuario administrador.');
                return 1;
            }
            
            if ($simulate) {
                $this->info("\nSe enviaría correo a: {$admin->email}");
                $this->info("Publicaciones para HOY: {$publicacionesHoy->count()}");
                $this->info("Publicaciones para MAÑANA: {$publicacionesManana->count()}");
                $this->info("Publicaciones ATRASADAS: {$publicacionesAtrasadas->count()}");
                
                // Mostrar detalles
                if ($publicacionesHoy->isNotEmpty()) {
                    $this->info("\n--- Para Hoy ({$hoy->format('d/m/Y')}) ---");
                    foreach ($publicacionesHoy as $pub) {
                        $this->line("- [{$pub->plataforma_nombre}] {$pub->cliente_nombre} - {$pub->formato_nombre}");
                    }
                }
                
                if ($publicacionesManana->isNotEmpty()) {
                    $this->info("\n--- Para Mañana ({$manana->format('d/m/Y')}) ---");
                    foreach ($publicacionesManana as $pub) {
                        $this->line("- [{$pub->plataforma_nombre}] {$pub->cliente_nombre} - {$pub->formato_nombre}");
                    }
                }
                
                if ($publicacionesAtrasadas->isNotEmpty()) {
                    $this->info("\n--- Atrasadas ---");
                    foreach ($publicacionesAtrasadas as $pub) {
                        $fecha = Carbon::parse($pub->fecha)->format('d/m/Y');
                        $this->line("- [{$pub->plataforma_nombre}] {$pub->cliente_nombre} - {$pub->formato_nombre} (Fecha: {$fecha})");
                    }
                }
            } else {
                // Enviar correo real
                Mail::to($admin->email)->send(
                    new RecordatorioPublicaciones($publicacionesHoy, $publicacionesManana, $publicacionesAtrasadas)
                );
                
                $this->info("Correo enviado a {$admin->email}");
                $this->info("Total de publicaciones notificadas: {$totalPublicaciones}");
            }
        } else {
            $this->info('No hay publicaciones pendientes que requieran recordatorio.');
        }
        
        return 0;
    }
}
