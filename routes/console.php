<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// DESACTIVADO: Google Calendar maneja el envío de notificaciones
// Los recordatorios se guardan en BD y se sincronizan con Google Calendar
// pero el Job de envío interno NO se ejecuta para evitar duplicados
// Schedule::job(new \App\Jobs\EnviarRecordatoriosEventos)
//     ->everyFiveMinutes()
//     ->withoutOverlapping()
//     ->name('enviar-recordatorios-eventos');

// Programar verificación de Briefs diariamente a las 9:00 AM
Schedule::command('briefs:check-status')->dailyAt('09:00');
