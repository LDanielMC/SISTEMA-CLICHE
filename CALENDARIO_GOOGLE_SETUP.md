# 📅 Configuración de Google Calendar API

## Paso 1: Crear Proyecto en Google Cloud Console

1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Crea un nuevo proyecto o selecciona uno existente
3. Nombra tu proyecto (ej: "Sistema Cliché - Calendario")

## Paso 2: Habilitar Google Calendar API

1. En el menú lateral, ve a **"APIs y servicios"** → **"Biblioteca"**
2. Busca **"Google Calendar API"**
3. Haz clic en **"Habilitar"**

## Paso 3: Configurar Pantalla de Consentimiento OAuth

1. Ve a **"APIs y servicios"** → **"Pantalla de consentimiento de OAuth"**
2. Selecciona **"Externo"** (o "Interno" si es G Suite)
3. Completa la información:
   - **Nombre de la aplicación**: Sistema Cliché
   - **Correo electrónico de asistencia**: tu correo
   - **Logotipo de la aplicación**: (opcional)
   - **Dominio de la aplicación**: tu dominio
   - **Dominios autorizados**: tu dominio
   - **Correo de contacto del desarrollador**: tu correo

4. En **"Ámbitos"**, agrega:
   - `https://www.googleapis.com/auth/calendar`
   - `https://www.googleapis.com/auth/calendar.events`

5. Guarda y continúa

## Paso 4: Crear Credenciales OAuth 2.0

1. Ve a **"APIs y servicios"** → **"Credenciales"**
2. Haz clic en **"+ CREAR CREDENCIALES"** → **"ID de cliente de OAuth 2.0"**
3. Tipo de aplicación: **"Aplicación web"**
4. Nombre: **"Sistema Cliché Web"**
5. **URIs de redireccionamiento autorizados**:
   ```
   http://localhost:8000/google/callback
   http://tu-dominio.com/google/callback
   ```
6. Haz clic en **"Crear"**

7. **IMPORTANTE**: Guarda el **Client ID** y **Client Secret** que aparecen

## Paso 5: Configurar Variables de Entorno

1. Abre tu archivo `.env`
2. Agrega las credenciales obtenidas:

```env
GOOGLE_CALENDAR_CLIENT_ID=tu_client_id_aqui.apps.googleusercontent.com
GOOGLE_CALENDAR_CLIENT_SECRET=tu_client_secret_aqui
GOOGLE_CALENDAR_REDIRECT_URI=http://localhost:8000/google/callback
GOOGLE_CALENDAR_ID=primary
GOOGLE_CALENDAR_SYNC_ENABLED=true
GOOGLE_CALENDAR_AUTO_SYNC=true
```

## Paso 6: Autenticar la Aplicación

1. Inicia tu aplicación Laravel
2. Inicia sesión como administrador
3. Ve al calendario: `http://localhost:8000/eventos`
4. Verifica el estado de autenticación llamando a: `http://localhost:8000/google/status`
5. Si no está autenticado, ve a: `http://localhost:8000/google/auth`
6. Autoriza la aplicación con tu cuenta de Google
7. Serás redirigido de vuelta a la aplicación

## Paso 7: Verificar Funcionamiento

1. Crea un evento en el sistema
2. Verifica que aparezca en tu Google Calendar
3. Edita el evento y verifica que se actualice
4. Elimina el evento y verifica que se elimine

## Programación de Recordatorios

Para que los recordatorios se envíen automáticamente, agrega esto al archivo `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule): void
{
    // Enviar recordatorios cada 5 minutos
    $schedule->job(new \App\Jobs\EnviarRecordatoriosEventos)
        ->everyFiveMinutes()
        ->withoutOverlapping();
}
```

Luego ejecuta el scheduler:

```bash
php artisan schedule:work
```

O en producción, agrega a crontab:

```bash
* * * * * cd /ruta-proyecto && php artisan schedule:run >> /dev/null 2>&1
```

## Solución de Problemas

### Error: "redirect_uri_mismatch"
- Verifica que la URI de redirección en Google Cloud Console coincida exactamente con la de tu `.env`

### Error: "invalid_client"
- Verifica que el Client ID y Client Secret sean correctos

### Error: "Access denied"
- Asegúrate de haber autorizado los scopes correctos en la pantalla de consentimiento

### Token expirado
- El sistema refresca automáticamente el token
- Si falla, vuelve a autenticar visitando `/google/auth`

## Características Implementadas

✅ **Sincronización Bidireccional**
- Los eventos creados en el sistema aparecen en Google Calendar
- Los cambios se sincronizan automáticamente

✅ **Recordatorios Personalizables**
- 10 minutos, 30 minutos, 1 hora, 2 horas, 1 día antes
- Por correo, sistema o ambos

✅ **Recurrencia**
- Diaria, semanal, mensual, anual
- Fecha de finalización personalizable

✅ **Participantes**
- Clientes, empleados o externos
- Invitaciones automáticas por correo

✅ **Notificaciones en Sistema**
- Integradas con el sistema de notificaciones existente
- Actualizaciones en tiempo real

## Notas Importantes

- **Seguridad**: Nunca compartas tus credenciales de Google
- **Token**: El token se guarda en `storage/app/google-calendar-token.json`
- **Límites**: Google Calendar API tiene límites de uso (verifica la consola)
- **Producción**: Usa HTTPS en producción para OAuth

## Soporte

Para más información consulta:
- [Documentación Google Calendar API](https://developers.google.com/calendar/api/guides/overview)
- [OAuth 2.0 Google](https://developers.google.com/identity/protocols/oauth2)
