<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Evento;
use App\Services\GoogleCalendarService;

echo "🔄 Sincronizando evento existente con Google Calendar\n";
echo "================================================\n\n";

$evento = Evento::first();

if (!$evento) {
    echo "❌ No hay eventos para sincronizar\n";
    exit;
}

echo "Evento encontrado:\n";
echo "- Título: {$evento->titulo}\n";
echo "- Fecha: {$evento->fecha->format('d/m/Y')}\n";
echo "- Hora: {$evento->hora_inicio} - {$evento->hora_fin}\n";
echo "- Ya sincronizado: " . ($evento->sincronizado_google ? 'SÍ' : 'NO') . "\n\n";

try {
    $service = new GoogleCalendarService();
    
    if (!$service->isAuthenticated()) {
        echo "❌ ERROR: Aplicación no autenticada con Google\n";
        echo "👉 Visita: http://127.0.0.1:8000/google/auth\n";
        exit(1);
    }
    
    echo "✅ Aplicación autenticada\n";
    echo "📤 Sincronizando con Google Calendar...\n\n";
    
    $googleEvent = $service->createEvent($evento);
    
    echo "✅ ¡Evento sincronizado exitosamente!\n";
    echo "Google Event ID: {$evento->google_event_id}\n";
    echo "\n🎉 Verifica en: https://calendar.google.com\n";
    
} catch (Exception $e) {
    echo "❌ Error al sincronizar: {$e->getMessage()}\n";
    echo "\nDetalles:\n";
    echo $e->getTraceAsString();
    exit(1);
}
