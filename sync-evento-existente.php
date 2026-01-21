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
    // DEBUG: Verificar configuración
    $tokenPath = config('google-calendar.token_path');
    echo "🔍 DEBUG - Información de autenticación:\n";
    echo "- Ruta del token: {$tokenPath}\n";
    echo "- Archivo existe: " . (file_exists($tokenPath) ? '✅ SÍ' : '❌ NO') . "\n";
    
    if (file_exists($tokenPath)) {
        echo "- Tamaño del archivo: " . filesize($tokenPath) . " bytes\n";
        echo "- Permisos: " . substr(sprintf('%o', fileperms($tokenPath)), -4) . "\n";
        echo "- Puede leer: " . (is_readable($tokenPath) ? '✅ SÍ' : '❌ NO') . "\n";
    }
    echo "\n";
    
    $service = new GoogleCalendarService();
    
    if (!$service->isAuthenticated()) {
        echo "❌ ERROR: Aplicación no autenticada con Google\n";
        echo "\n📋 Acciones a realizar:\n";
        echo "1. Verifica que el archivo token existe en: {$tokenPath}\n";
        echo "2. Si no existe, visita: http://127.0.0.1:8000/google/auth\n";
        echo "3. Verifica que el archivo tiene permisos de lectura\n";
        echo "\n💡 Ejecuta: chmod 644 {$tokenPath}\n";
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
