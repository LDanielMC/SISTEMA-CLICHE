<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Calendar API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuración para la integración con Google Calendar API
    |
    */

    'client_id' => env('GOOGLE_CALENDAR_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CALENDAR_CLIENT_SECRET'),
    'redirect_uri' => env('GOOGLE_CALENDAR_REDIRECT_URI', env('APP_URL') . '/google/callback'),
    
    // Calendar ID - por defecto usa el calendario principal
    'calendar_id' => env('GOOGLE_CALENDAR_ID', 'primary'),
    
    // Ruta donde se guardará el token de acceso
    'token_path' => storage_path('app/google-calendar-token.json'),
    
    // Scopes necesarios
    'scopes' => [
        'https://www.googleapis.com/auth/calendar',
        'https://www.googleapis.com/auth/calendar.events',
        'https://www.googleapis.com/auth/forms.body', // Escritura y lectura de estructura
        'https://www.googleapis.com/auth/forms.responses.readonly', // Leer respuestas
        'https://www.googleapis.com/auth/drive', // ✅ NECESARIO PARA BORRAR ARCHIVOS (FORMS)
    ],
    
    // Configuración de sincronización
    'sync_enabled' => env('GOOGLE_CALENDAR_SYNC_ENABLED', true),
    'auto_sync' => env('GOOGLE_CALENDAR_AUTO_SYNC', true),
];
