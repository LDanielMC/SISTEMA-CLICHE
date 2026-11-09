# SISTEMA-CLICHE

Sistema de gestión interna para una agencia de marketing digital: centraliza clientes, cotizaciones, tareas del equipo, calendario de publicaciones, suscripciones y reportes, con un portal para que el cliente conteste briefs. Lo usan tres perfiles: administrador, empleado y cliente.

## Funcionalidades principales

- **Roles y acceso:** administrador, empleado y cliente, con paneles distintos. Autenticación con Laravel Breeze (registro, verificación de correo y recuperación de contraseña).
- **Clientes y empleados:** alta, baja, edición y búsqueda; información fiscal por cliente y bitácora de cambios.
- **Cotizaciones:** partidas, IVA/ISR y exportación a PDF.
- **Tareas y asignaciones:** catálogo de tareas por categoría, asignación a empleados, evidencia, evaluación y vista "mis tareas".
- **Calendario de publicaciones:** por cliente, plataforma y formato, con recordatorios por correo.
- **Briefs:** formularios creados en Google Forms desde el sistema, asignados al cliente, con lectura de sus respuestas y revisión diaria de estatus.
- **Agenda y eventos:** sincronización con Google Calendar (OAuth).
- **Minutas y acuerdos:** registro de reuniones con acuerdos, responsables y estatus.
- **Suscripciones:** control de servicios recurrentes, renovaciones y recordatorios de vencimiento.
- **Reportes (admin):** cumplimiento y puntualidad, efectividad e ingresos, carga de trabajo, costos de suscripciones, acuerdos por cliente y crecimiento de clientes.
- **Respaldos de base de datos (admin):** generar, descargar, restaurar y eliminar respaldos `.sql`.
- **Notificaciones internas** dentro de la aplicación.

## Capturas

<!-- Agrega aquí tus imágenes, por ejemplo: ![Dashboard](docs/capturas/dashboard.png) -->

_Pendiente._

## Stack

| Capa | Tecnología |
|---|---|
| Backend | PHP ^8.2, Laravel 12 (12.38.1 en `composer.lock`), Laravel Sanctum 4 |
| Base de datos | MySQL (sesiones, cola y caché en base de datos) |
| Frontend | Blade, Tailwind CSS 3, Alpine.js 3, Vite 7, Axios |
| PDF | barryvdh/laravel-dompdf 3.1 |
| Integraciones | Google API Client 2.15 (Calendar y Forms) |
| Pruebas | Pest 4 con pest-plugin-laravel |
| Herramientas de desarrollo | Laravel Pint, Sail, Pail, Breeze |

## Arquitectura

Aplicación monolítica MVC con Laravel:

- `app/Http/Controllers/`: un controlador por módulo (clientes, cotizaciones, tareas, calendario, briefs, reportes, respaldos, etc.).
- `app/Http/Middleware/IsAdmin.php`: restringe a administradores las rutas de gestión; los empleados y clientes tienen rutas propias bajo `auth`.
- `app/Models/`: modelos Eloquent (Cliente, Empleado, Cotizacion, Tarea, AsignacionTarea, Publicacion, Brief, Minuta, Suscripcion, Evento, etc.).
- `app/Services/`: `GoogleCalendarService` y `GoogleFormsService`, que encapsulan la comunicación con las APIs de Google.
- `app/Console/Commands/` y `routes/console.php`: comandos programados (`briefs:check-status` corre diario a las 09:00) y de recordatorios (`publicaciones:recordatorios`, `suscripciones:recordatorios`).
- `database/migrations/` (48 migraciones) y `database/seeders/`; `diagrama_er_cliche.sql` y `documentacion_diagrama_er.md` documentan el modelo entidad-relación.
- `resources/views/`: vistas Blade por rol y módulo.

## Instalación y ejecución

Requisitos: PHP 8.2+, Composer, Node.js y npm, y MySQL.

```bash
git clone https://github.com/LDanielMC/SISTEMA-CLICHE.git
cd SISTEMA-CLICHE

# Instala dependencias, crea .env y APP_KEY, compila los assets
composer install
cp .env.example .env
php artisan key:generate
npm install
```

Configura en `.env` la conexión a MySQL (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) y luego:

```bash
php artisan migrate --seed   # crea las tablas y datos iniciales (usuarios, categorías)
composer dev                 # servidor, cola y Vite en paralelo
```

La app queda en `http://localhost:8000`. Los datos iniciales salen de `database/seeders/`; revísalos y cambia las contraseñas de ejemplo antes de desplegar.

`composer setup` ejecuta la instalación, las migraciones y `npm run build` en un solo paso.

### Variables de entorno relevantes

| Variable | Uso |
|---|---|
| `DB_*` | Conexión a MySQL |
| `GOOGLE_CALENDAR_CLIENT_ID`, `GOOGLE_CALENDAR_CLIENT_SECRET`, `GOOGLE_CALENDAR_REDIRECT_URI` | OAuth de Google (Calendar y Forms) |
| `GOOGLE_CALENDAR_ID`, `GOOGLE_CALENDAR_SYNC_ENABLED`, `GOOGLE_CALENDAR_AUTO_SYNC` | Sincronización de eventos |
| `MYSQLDUMP_PATH` | Ruta del ejecutable `mysqldump` para los respaldos (por defecto apunta a una instalación de WAMP en Windows) |
| `MAIL_*` | Envío de recordatorios por correo |

Configuración paso a paso de Google Calendar en [CALENDARIO_GOOGLE_SETUP.md](CALENDARIO_GOOGLE_SETUP.md) y del módulo de suscripciones en [MODULO_SUSCRIPCIONES_README.md](MODULO_SUSCRIPCIONES_README.md).

### Tareas programadas

Para que corran los comandos programados, agrega al cron del servidor:

```
* * * * * cd /ruta/al/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

## Pruebas

```bash
composer test    # limpia la caché de configuración y ejecuta php artisan test (Pest)
```

Las pruebas automatizadas corren con SQLite en memoria y cubren la autenticación (login, registro, verificación de correo, recuperación y cambio de contraseña) y el perfil. Los módulos de negocio se validaron con pruebas funcionales manuales documentadas en [docs/pruebas/](docs/pruebas/) (un archivo por requerimiento funcional).

## Documentación adicional

- [DESIGN_SYSTEM.md](DESIGN_SYSTEM.md): sistema de diseño de la interfaz.
- [docs/](docs/): seguridad y respaldos, ambiente de pruebas y base de datos de pruebas (datos ficticios).
