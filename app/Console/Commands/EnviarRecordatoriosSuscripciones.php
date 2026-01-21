<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Suscripcion;
use App\Models\User;
use App\Mail\RecordatorioSuscripciones;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EnviarRecordatoriosSuscripciones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suscripciones:recordatorios {--simulate : Simular envío sin mandar correos reales}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar recordatorios de suscripciones por vencer y vencidas al administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $simulate = $this->option('simulate');
        $hoy = Carbon::today();
        
        if ($simulate) {
            $this->info('=== MODO SIMULACIÓN ===');
        }
        
        // Obtener todas las suscripciones activas
        $suscripciones = Suscripcion::where('estatus', 'activo')->get();
        
        $suscripcionesPorVencer = collect();
        $suscripcionesVencidas = collect();
        $recordatoriosEnviados = 0;
        
        foreach ($suscripciones as $suscripcion) {
            $diasRestantes = $suscripcion->dias_restantes;
            
            // Suscripciones vencidas
            if ($diasRestantes < 0) {
                // Verificar si ya se envió recordatorio hoy
                if (!$this->yaSeEnvioHoy($suscripcion->idSuscripcion, $hoy)) {
                    $suscripcionesVencidas->push($suscripcion);
                    $this->registrarRecordatorio($suscripcion->idSuscripcion, $hoy, $diasRestantes, $simulate);
                    $recordatoriosEnviados++;
                }
                continue;
            }
            
            // Suscripciones por vencer según días de recordatorio
            if ($suscripcion->dias_recordatorio && is_array($suscripcion->dias_recordatorio)) {
                foreach ($suscripcion->dias_recordatorio as $dia) {
                    if ($diasRestantes == $dia) {
                        // Verificar si ya se envió recordatorio hoy
                        if (!$this->yaSeEnvioHoy($suscripcion->idSuscripcion, $hoy)) {
                            $suscripcionesPorVencer->push($suscripcion);
                            $this->registrarRecordatorio($suscripcion->idSuscripcion, $hoy, $diasRestantes, $simulate);
                            $recordatoriosEnviados++;
                        }
                        break;
                    }
                }
            }
        }
        
        // Enviar correo si hay suscripciones para notificar
        if ($suscripcionesPorVencer->isNotEmpty() || $suscripcionesVencidas->isNotEmpty()) {
            // Obtener email del admin
            $admin = User::where('rol', 'admin')->first();
            
            if (!$admin) {
                $this->error('No se encontró un usuario administrador.');
                return 1;
            }
            
            if ($simulate) {
                $this->info("\nSe enviaría correo a: {$admin->email}");
                $this->info("Suscripciones por vencer: {$suscripcionesPorVencer->count()}");
                $this->info("Suscripciones vencidas: {$suscripcionesVencidas->count()}");
                
                // Mostrar detalles
                if ($suscripcionesPorVencer->isNotEmpty()) {
                    $this->info("\n--- Por Vencer ---");
                    foreach ($suscripcionesPorVencer as $sus) {
                        $this->line("- {$sus->nombre_servicio}: {$sus->dias_restantes} días");
                    }
                }
                
                if ($suscripcionesVencidas->isNotEmpty()) {
                    $this->info("\n--- Vencidas ---");
                    foreach ($suscripcionesVencidas as $sus) {
                        $this->line("- {$sus->nombre_servicio}: vencida hace " . abs($sus->dias_restantes) . " días");
                    }
                }
            } else {
                // Enviar correo real
                Mail::to($admin->email)->send(
                    new RecordatorioSuscripciones($suscripcionesPorVencer, $suscripcionesVencidas)
                );
                
                $this->info("Correo enviado a {$admin->email}");
                $this->info("Recordatorios procesados: {$recordatoriosEnviados}");
            }
        } else {
            $this->info('No hay suscripciones que requieran recordatorio hoy.');
        }
        
        return 0;
    }
    
    /**
     * Verificar si ya se envió recordatorio hoy para esta suscripción.
     */
    private function yaSeEnvioHoy($idSuscripcion, $fecha)
    {
        return DB::table('suscripcion_recordatorios_log')
            ->where('idSuscripcion', $idSuscripcion)
            ->where('fecha_recordatorio', $fecha)
            ->exists();
    }
    
    /**
     * Registrar recordatorio en el log.
     */
    private function registrarRecordatorio($idSuscripcion, $fecha, $diasRestantes, $simulate = false)
    {
        if ($simulate) {
            return;
        }
        
        DB::table('suscripcion_recordatorios_log')->insert([
            'idSuscripcion' => $idSuscripcion,
            'fecha_recordatorio' => $fecha,
            'dias_restantes' => $diasRestantes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
