# CAPÍTULO 4. IMPLEMENTACIÓN
## Sección: Configuración de Seguridad del Sistema

---

## 4.X CONFIGURACIÓN DEL ENTORNO (.env)

El archivo `.env` concentra todas las variables de entorno sensibles del sistema. Laravel lo carga automáticamente y **nunca se versiona en el repositorio** (está incluido en `.gitignore`) para proteger credenciales.

Las variables más relevantes para la seguridad son las siguientes:

```
APP_KEY=base64:...              ← Clave maestra para cifrado de sesiones y cookies
APP_ENV=local                   ← Entorno de ejecución
APP_DEBUG=false                 ← En producción debe ser false

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clichesgi
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database         ← Sesiones almacenadas en base de datos
SESSION_LIFETIME=20             ← Expiración por inactividad (20 minutos)
SESSION_ENCRYPT=false

GOOGLE_CALENDAR_CLIENT_ID=...
GOOGLE_CALENDAR_CLIENT_SECRET=...
GOOGLE_CALENDAR_REDIRECT_URI=...

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=...
MAIL_PASSWORD=...
```

> 📸 **[CAPTURA AQUÍ]** Toma una captura del archivo `.env` real (ocultando contraseñas y secrets con `****`). Puedes abrir el archivo desde el IDE y hacer captura mostrando las secciones de DB, SESSION y GOOGLE.
>
> **Pie de figura sugerido:**
> *Figura 4.X. Variables de entorno del sistema Cliché SGI (.env).*
>
> **Descripción sugerida para el documento:**
> *En la figura 4.X se muestra la configuración del archivo de entorno del sistema. Las credenciales sensibles como claves de API, contraseñas y tokens se almacenan exclusivamente en este archivo, el cual se excluye del control de versiones mediante la entrada correspondiente en `.gitignore`, garantizando que no sean expuestas públicamente.*

---

## 4.X CONFIGURACIÓN DE SESIÓN

Las sesiones del sistema se configuran en `config/session.php`. Se utiliza el driver `database`, lo que significa que cada sesión se almacena en la tabla `sessions` de la base de datos, permitiendo control centralizado y auditoría.

| Parámetro | Valor configurado | Descripción |
|-----------|------------------|-------------|
| `driver` | `database` | Sesiones en tabla `sessions` de MySQL |
| `lifetime` | `20` minutos | Tiempo de inactividad antes de expirar |
| `expire_on_close` | `false` | La sesión persiste aunque se cierre el navegador |
| `encrypt` | `false` | Payload de sesión sin cifrado adicional |
| `http_only` | `true` | Cookie no accesible desde JavaScript |
| `same_site` | `lax` | Protección contra ataques CSRF cross-site |

La tabla `sessions` almacena: el ID de sesión, el `user_id`, la IP del cliente, el User-Agent y el timestamp de última actividad.

> 📸 **[CAPTURA AQUÍ]** Abre `config/session.php` y captura las líneas 21–50 donde se ven los parámetros `driver`, `lifetime` y `expire_on_close`.
>
> **Pie de figura sugerido:**
> *Figura 4.X. Configuración de sesiones en `config/session.php`.*
>
> **Descripción sugerida:**
> *En la figura 4.X se muestra la configuración de sesiones del sistema. El driver `database` garantiza que las sesiones sean persistentes y auditables, mientras que el tiempo de expiración de 20 minutos por inactividad refuerza la seguridad ante accesos no autorizados.*

---

## 4.X CONFIGURACIÓN DE BASE DE DATOS Y CORREO

### Base de datos

La conexión a la base de datos se configura mediante variables de entorno leídas por `config/database.php`. Se utiliza MySQL sobre la dirección `127.0.0.1` (localhost) con el puerto estándar `3306`.

### Correo electrónico

El sistema utiliza SMTP para el envío de notificaciones y recordatorios automáticos. En el entorno de desarrollo se emplea Mailtrap como servidor de pruebas, lo que evita el envío real de correos durante el desarrollo.

```
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@cliche.com"
MAIL_FROM_NAME="Cliché SGI"
```

> 📸 **[CAPTURA OPCIONAL]** Si el documento lo requiere, captura la sección `MAIL_*` del `.env.example` (el archivo de ejemplo que sí se puede mostrar sin riesgo).
>
> **Pie de figura sugerido:**
> *Figura 4.X. Configuración del servicio de correo electrónico.*

---

## 4.X RUTAS PROTEGIDAS Y MIDDLEWARE POR ROL

### Registro del middleware personalizado

En Laravel 12, los middlewares de alias se registran en `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'admin' => \App\Http\Middleware\IsAdmin::class,
    ]);
})
```

> 📸 **[CAPTURA AQUÍ]** Captura el archivo `bootstrap/app.php` completo (solo tiene 22 líneas).
>
> **Pie de figura sugerido:**
> *Figura 4.X. Registro del middleware personalizado `admin` en `bootstrap/app.php`.*
>
> **Descripción sugerida:**
> *En la figura 4.X se muestra el registro del middleware de rol en el archivo de arranque de la aplicación. El alias `admin` referencia la clase `IsAdmin`, que es evaluada en cada petición que pertenezca al grupo de rutas administrativas.*

---

### Middleware IsAdmin

El middleware `IsAdmin` intercepta todas las peticiones hacia rutas administrativas y verifica que el usuario autenticado tenga el rol `admin`. Si no lo tiene, responde con un error HTTP 403.

```php
class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->rol !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        return $next($request);
    }
}
```

> 📸 **[CAPTURA AQUÍ]** Captura el archivo `app/Http/Middleware/IsAdmin.php`.
>
> **Pie de figura sugerido:**
> *Figura 4.X. Middleware `IsAdmin` para control de acceso por rol.*
>
> **Descripción sugerida:**
> *En la figura 4.X se muestra la implementación del middleware de rol del sistema. El método `handle()` verifica en cada petición que el usuario esté autenticado y que su campo `rol` sea `admin`; de lo contrario, interrumpe la ejecución y retorna una respuesta HTTP 403 (Prohibido).*

---

### Grupos de rutas por rol

Las rutas del sistema se organizan en grupos protegidos con combinaciones de middleware:

```php
// Rutas exclusivas para administrador
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', ...);
    Route::resource('empleados', EmpleadoController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('cotizaciones', CotizacionController::class);
    // ... todas las rutas administrativas
});

// Rutas para usuarios autenticados (empleados y admin)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/empleado/dashboard', function () {
        if (Auth::user()->rol !== 'empleado') {
            abort(403, 'No autorizado.');
        }
        return view('portal_empleado.dashboard');
    });
});

// Rutas para clientes autenticados
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/cliente/dashboard', [PortalClienteController::class, 'dashboard']);
    Route::get('/cliente/briefs',    [PortalClienteController::class, 'briefs']);
});
```

> 📸 **[CAPTURA AQUÍ]** Captura `routes/web.php` mostrando las líneas 21–55 donde se ven los tres grupos de rutas con sus middlewares.
>
> **Pie de figura sugerido:**
> *Figura 4.X. Grupos de rutas protegidas por middleware en `routes/web.php`.*
>
> **Descripción sugerida:**
> *En la figura 4.X se muestra la organización de rutas protegidas del sistema. Se definen tres niveles de acceso: rutas exclusivas de administrador protegidas con `['auth', 'admin']`, rutas de empleado protegidas con `['auth', 'verified']` y rutas del portal de cliente también bajo autenticación verificada.*

---

## 4.X VALIDACIÓN DE REQUESTS

Laravel proporciona el mecanismo de Form Requests para centralizar la validación de datos de entrada. El sistema utiliza este mecanismo en los módulos críticos.

### Ejemplo: Validación de inicio de sesión

```php
// app/Http/Requests/Auth/LoginRequest.php
public function rules(): array
{
    return [
        'email'    => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ];
}

// Rate Limiting: máximo 5 intentos fallidos
public function ensureIsNotRateLimited(): void
{
    if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
        return;
    }
    event(new Lockout($this));
    // lanza excepción con tiempo de espera
}
```

### Ejemplo: Validación de subida de evidencia

```php
$request->validate([
    'evidencia' => 'required|file|mimes:pdf|max:10240',
], [
    'evidencia.required' => 'Debe seleccionar un archivo.',
    'evidencia.mimes'    => 'El archivo debe ser un PDF.',
    'evidencia.max'      => 'El archivo no debe superar 10MB.',
]);
```

> 📸 **[CAPTURA AQUÍ]** Captura `app/Http/Requests/Auth/LoginRequest.php` mostrando `rules()` y `ensureIsNotRateLimited()`.
>
> **Pie de figura sugerido:**
> *Figura 4.X. Validación de solicitud de inicio de sesión con rate limiting.*
>
> **Descripción sugerida:**
> *En la figura 4.X se muestra la clase `LoginRequest` encargada de validar las credenciales de acceso. Además de las reglas básicas de formato, implementa un mecanismo de rate limiting que bloquea temporalmente el acceso tras cinco intentos fallidos consecutivos desde la misma IP, mitigando ataques de fuerza bruta.*

---

## 4.X INTEGRACIÓN OAUTH CON GOOGLE CALENDAR Y FORMS

### Configuración de la integración

La integración con Google se configura en `config/google-calendar.php`, leyendo las credenciales desde el archivo `.env`:

```php
'client_id'     => env('GOOGLE_CALENDAR_CLIENT_ID'),
'client_secret' => env('GOOGLE_CALENDAR_CLIENT_SECRET'),
'redirect_uri'  => env('GOOGLE_CALENDAR_REDIRECT_URI'),
'token_path'    => storage_path('app/google-calendar-token.json'),
```

### Scopes solicitados

Los permisos (scopes) solicitados a Google al momento de autorizar son:

| Scope | Propósito |
|-------|-----------|
| `calendar` | Lectura y escritura en el calendario |
| `calendar.events` | Gestión de eventos individuales |
| `forms.body` | Lectura y estructura de formularios |
| `forms.responses.readonly` | Leer respuestas de formularios (briefs) |
| `drive` | Requerido para eliminar archivos de Google Forms |

### Almacenamiento del token

El token de acceso OAuth se almacena en el servidor en:
```
storage/app/google-calendar-token.json
```

El archivo contiene el `access_token`, `refresh_token`, tiempo de expiración y tipo de token en formato JSON. **Este archivo no se versiona** (excluido por `.gitignore`) y tiene permisos de directorio `0700`.

### Expiración y renovación automática

```php
private function loadToken()
{
    $tokenPath = config('google-calendar.token_path');

    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $this->client->setAccessToken($accessToken);

        // Si el token expiró, se renueva automáticamente con el refresh token
        if ($this->client->isAccessTokenExpired()) {
            if ($this->client->getRefreshToken()) {
                $this->client->fetchAccessTokenWithRefreshToken(
                    $this->client->getRefreshToken()
                );
                file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
            }
        }
    }
}
```

Los tokens de Google tienen una duración de **1 hora** para el `access_token`. El `refresh_token` no expira automáticamente y permite obtener nuevos `access_token` sin intervención del usuario. El sistema detecta la expiración con `isAccessTokenExpired()` y renueva el token en cada instancia del servicio.

> 📸 **[CAPTURA AQUÍ]** Captura `app/Services/GoogleCalendarService.php` mostrando el constructor y el método `loadToken()` (líneas 19–54).
>
> **Pie de figura sugerido:**
> *Figura 4.X. Gestión de tokens OAuth en `GoogleCalendarService`.*
>
> **Descripción sugerida:**
> *En la figura 4.X se muestra el mecanismo de autenticación OAuth 2.0 con Google. El servicio carga el token desde el sistema de archivos del servidor, verifica su vigencia con `isAccessTokenExpired()` y, en caso de expiración, lo renueva automáticamente mediante el `refresh_token` sin requerir intervención del usuario ni una nueva autorización.*

---

> 📸 **[CAPTURA ADICIONAL]** Captura `config/google-calendar.php` completo mostrando los scopes.
>
> **Pie de figura sugerido:**
> *Figura 4.X. Configuración de scopes OAuth y ruta de almacenamiento del token.*
>
> **Descripción sugerida:**
> *En la figura 4.X se muestran los permisos (scopes) solicitados a la API de Google. Cada scope corresponde a un acceso específico y mínimo necesario para las funcionalidades del sistema: gestión de eventos en Google Calendar y lectura de respuestas en Google Forms.*

---

## 4.X SEGURIDAD EN SUBIDA DE EVIDENCIAS PDF

El sistema permite a los empleados subir archivos PDF como evidencia de tareas completadas. El proceso implementa múltiples capas de seguridad:

### Validación del archivo

```php
$request->validate([
    'evidencia' => 'required|file|mimes:pdf|max:10240',
]);
```

| Restricción | Valor | Descripción |
|-------------|-------|-------------|
| Tipo permitido | Solo `pdf` | Validado por extensión y MIME type |
| Tamaño máximo | 10 MB (10,240 KB) | Previene subida de archivos excesivos |
| Obligatorio | Sí | No se procesa sin archivo |

### Ruta de almacenamiento

```php
$path = $request->file('evidencia')->store('evidencias', 'public');
```

Los archivos se almacenan en:
```
storage/app/public/evidencias/[nombre_generado_por_laravel].pdf
```

Laravel genera automáticamente un nombre de archivo único y aleatorio, evitando que archivos maliciosos se suban con nombres predecibles o sobrescriban archivos existentes.

### Control de acceso al archivo

El acceso al archivo está controlado por el método `verEvidencia()` que aplica verificación de permisos antes de servir cualquier archivo:

```php
// Solo admin o el empleado propietario pueden ver la evidencia
if ($user->rol === 'admin') {
    // Admin tiene acceso total
} elseif ($user->rol === 'empleado') {
    if (!$user->empleado ||
        (int)$user->empleado->id_empleado !== (int)$asignacion->empleado_id) {
        abort(403, 'No autorizado para ver esta evidencia.');
    }
} else {
    abort(403, 'No autorizado.');
}

// Validación de existencia física del archivo en disco
if (!Storage::disk('public')->exists($asignacion->evidencia_path)) {
    abort(404, 'No se encontró el archivo de evidencia.');
}
```

> 📸 **[CAPTURA AQUÍ]** Captura `app/Http/Controllers/AsignacionTareaController.php` mostrando el método `subirEvidencia()` (líneas 177–197).
>
> **Pie de figura sugerido:**
> *Figura 4.X. Validación de seguridad en la subida de evidencias PDF.*
>
> **Descripción sugerida:**
> *En la figura 4.X se muestra la validación aplicada al proceso de subida de archivos de evidencia. El sistema restringe el tipo de archivo a PDF mediante validación de MIME type, limita el tamaño máximo a 10 MB y almacena el archivo con un nombre generado aleatoriamente por Laravel en una ruta controlada del servidor, previniendo la ejecución de archivos maliciosos.*

---

> 📸 **[CAPTURA AQUÍ]** Captura `app/Http/Controllers/AsignacionTareaController.php` mostrando el método `verEvidencia()` (líneas 219–260).
>
> **Pie de figura sugerido:**
> *Figura 4.X. Control de acceso al archivo de evidencia en `verEvidencia()`.*
>
> **Descripción sugerida:**
> *En la figura 4.X se muestra el control de acceso implementado para la visualización de evidencias. El sistema valida el rol del usuario antes de servir el archivo: solo el administrador o el empleado propietario de la asignación pueden acceder a la evidencia. Adicionalmente, se verifica la existencia física del archivo en el disco antes de intentar servirlo, respondiendo con un error HTTP 404 si el archivo no se encuentra.*

---

## 4.X MIGRACIONES DE LARAVEL

El esquema de la base de datos se define y versiona mediante el sistema de migraciones de Laravel. Cada migración es un archivo PHP con dos métodos: `up()` para aplicar el cambio y `down()` para revertirlo. Esto permite reproducir el esquema completo en cualquier entorno con el comando `php artisan migrate`.

El sistema cuenta con **42 migraciones** organizadas cronológicamente desde noviembre de 2025:

### Migración inicial: tabla `users` y `sessions`

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});

Schema::create('sessions', function (Blueprint $table) {
    $table->string('id')->primary();
    $table->foreignId('user_id')->nullable()->index();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->longText('payload');
    $table->integer('last_activity')->index();
});
```

### Migración de empleados con clave foránea

```php
Schema::create('empleados', function (Blueprint $table) {
    $table->bigIncrements('id_empleado');
    $table->string('nombre', 120);
    $table->string('apellido_paterno', 120);
    $table->enum('estatus', ['activo','inactivo','baja'])->default('activo');
    $table->unsignedBigInteger('id_usuario');
    $table->foreign('id_usuario')
          ->references('id')->on('users')
          ->onUpdate('cascade')
          ->onDelete('restrict');
    $table->index('id_usuario', 'ix_empleado_usuario');
});
```

### Lista completa de migraciones (cronológica)

| Fecha | Migración |
|-------|-----------|
| 2025-11-15 | `create_users_table` / `create_sessions_table` |
| 2025-11-15 | `add_rol_to_users_table` |
| 2025-11-15 | `create_empleados_table` |
| 2025-11-15 | `create_clientes_table` |
| 2025-11-19 | `create_personal_access_tokens_table` |
| 2025-11-22 | `create_info_fiscal_table` |
| 2025-11-24 | `create_cotizaciones_table` / `create_cotizacion_detalles_table` |
| 2026-01-10 | `create_categorias_table` / `create_tareas_table` |
| 2026-01-10 | `create_bitacora_clientes_table` |
| 2026-01-11 | `create_asignaciones_tareas_table` |
| 2026-01-11 | `create_plataformas_table` / `create_formatos_table` / `create_publicaciones_table` |
| 2026-01-12 | `create_notificaciones_table` |
| 2026-01-13 | `create_eventos_table` / `create_evento_participantes_table` / `create_evento_recordatorios_table` |
| 2026-01-17 | `create_minutas_table` / `create_acuerdos_table` |
| 2026-01-19 | `create_briefs_table` |
| 2026-01-19 | `create_categorias_suscripcion_table` / `create_suscripciones_table` |

> 📸 **[CAPTURA AQUÍ]** Ejecuta `php artisan migrate:status` en la terminal y captura la salida mostrando todas las migraciones con estado "Ran".
>
> **Pie de figura sugerido:**
> *Figura 4.X. Estado de migraciones del sistema (`php artisan migrate:status`).*
>
> **Descripción sugerida:**
> *En la figura 4.X se muestra el estado de las migraciones del sistema mediante el comando `php artisan migrate:status`. Cada fila representa una migración ejecutada, con su fecha de aplicación. Las 42 migraciones definen de forma versionada y reproducible el esquema completo de la base de datos del sistema Cliché SGI.*

---

> 📸 **[CAPTURA ADICIONAL]** Captura el explorador de archivos del IDE mostrando la carpeta `database/migrations/` con todos los archivos listados.
>
> **Pie de figura sugerido:**
> *Figura 4.X. Archivos de migración en `database/migrations/`.*
>
> **Descripción sugerida:**
> *En la figura 4.X se muestra el directorio de migraciones del proyecto. Cada archivo PHP representa un cambio incremental y versionado en el esquema de la base de datos, permitiendo reproducir la estructura completa del sistema en cualquier entorno de desarrollo o producción mediante el comando `php artisan migrate`.*

---

## RESUMEN DE CAPTURAS NECESARIAS

| # | Archivo a capturar | Líneas | Figura sugerida |
|---|--------------------|--------|-----------------|
| 1 | `.env` (con datos ocultos) | Todas | 4.X Variables de entorno |
| 2 | `config/session.php` | 21–50 | 4.X Configuración de sesión |
| 3 | `bootstrap/app.php` | Todas | 4.X Registro de middleware |
| 4 | `app/Http/Middleware/IsAdmin.php` | Todas | 4.X Middleware por rol |
| 5 | `routes/web.php` | 21–55 | 4.X Rutas protegidas |
| 6 | `app/Http/Requests/Auth/LoginRequest.php` | 41–79 | 4.X Validación de login |
| 7 | `config/google-calendar.php` | Todas | 4.X Scopes OAuth Google |
| 8 | `app/Services/GoogleCalendarService.php` | 19–54 | 4.X Tokens OAuth |
| 9 | `app/Http/Controllers/AsignacionTareaController.php` | 177–197 | 4.X Subida de evidencias |
| 10 | `app/Http/Controllers/AsignacionTareaController.php` | 219–260 | 4.X Control de acceso evidencias |
| 11 | Terminal: `php artisan migrate:status` | Salida completa | 4.X Estado de migraciones |
| 12 | IDE: carpeta `database/migrations/` | Vista de archivos | 4.X Lista de migraciones |
