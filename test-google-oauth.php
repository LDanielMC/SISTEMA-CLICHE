<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Diagnóstico de Google Calendar OAuth\n";
echo "========================================\n\n";

// 1. Verificar configuración
echo "1️⃣ Configuración:\n";
echo "   CLIENT_ID: " . (config('google-calendar.client_id') ? '✅ Configurado' : '❌ NO configurado') . "\n";
echo "   CLIENT_SECRET: " . (config('google-calendar.client_secret') ? '✅ Configurado' : '❌ NO configurado') . "\n";
echo "   REDIRECT_URI: " . config('google-calendar.redirect_uri') . "\n";
echo "   TOKEN_PATH: " . config('google-calendar.token_path') . "\n\n";

// 2. Verificar directorio
$tokenPath = config('google-calendar.token_path');
$tokenDir = dirname($tokenPath);
echo "2️⃣ Directorio del token:\n";
echo "   Ruta: $tokenDir\n";
echo "   Existe: " . (is_dir($tokenDir) ? '✅ SÍ' : '❌ NO') . "\n";

if (is_dir($tokenDir)) {
    echo "   Escribible: " . (is_writable($tokenDir) ? '✅ SÍ' : '❌ NO') . "\n";
    echo "   Permisos: " . substr(sprintf('%o', fileperms($tokenDir)), -4) . "\n";
}

// 3. Verificar archivo de token
echo "\n3️⃣ Archivo de token:\n";
echo "   Existe: " . (file_exists($tokenPath) ? '✅ SÍ' : '❌ NO') . "\n";

if (file_exists($tokenPath)) {
    echo "   Tamaño: " . filesize($tokenPath) . " bytes\n";
    echo "   Contenido:\n";
    $token = json_decode(file_get_contents($tokenPath), true);
    echo "   - access_token: " . (isset($token['access_token']) ? '✅ Presente' : '❌ Ausente') . "\n";
    echo "   - refresh_token: " . (isset($token['refresh_token']) ? '✅ Presente' : '❌ Ausente') . "\n";
    echo "   - expires_in: " . ($token['expires_in'] ?? 'N/A') . "\n";
}

// 4. Probar servicio
echo "\n4️⃣ Prueba del servicio:\n";
try {
    $service = new \App\Services\GoogleCalendarService();
    echo "   Servicio creado: ✅\n";
    echo "   Autenticado: " . ($service->isAuthenticated() ? '✅ SÍ' : '❌ NO') . "\n";
    
    if (!$service->isAuthenticated()) {
        echo "\n   🔗 URL de autenticación:\n";
        echo "   " . $service->getAuthUrl() . "\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n========================================\n";
echo "🎯 Recomendaciones:\n\n";

if (!file_exists($tokenPath)) {
    echo "1. El token NO existe. Debes autenticar:\n";
    echo "   a) Ve a: http://127.0.0.1:8000/google/auth\n";
    echo "   b) Autoriza con la cuenta de Google configurada para el calendario\n";
    echo "   c) Vuelve a ejecutar este script\n\n";
}

if (!is_writable($tokenDir)) {
    echo "2. El directorio no es escribible. Ejecuta:\n";
    echo "   chmod 775 $tokenDir\n\n";
}
