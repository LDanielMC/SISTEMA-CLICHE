# 📦 Módulo de Gestión de Suscripciones

## 📋 Descripción General

Sistema completo para administrar suscripciones de servicios digitales, herramientas, plataformas, dominios y licencias de software utilizadas por la empresa o para clientes. Incluye gestión de categorías, renovaciones automáticas, recordatorios por correo y reportes de vencimientos.

---

## ✅ Funcionalidades Implementadas

### 🎯 Gestión de Suscripciones
- ✅ **CRUD completo** de suscripciones (crear, leer, actualizar, eliminar)
- ✅ **Baja lógica**: Las suscripciones se marcan como inactivas en lugar de eliminarse
- ✅ **Reactivación**: Posibilidad de reactivar suscripciones inactivas
- ✅ **Cálculo automático** de fecha de vencimiento según periodicidad (mensual/anual)
- ✅ **Días restantes** calculados dinámicamente (no guardados en BD)
- ✅ **Estados visuales**: OK, Por vencer, Vencida

### 📂 Categorías de Suscripción
- ✅ CRUD completo de categorías
- ✅ Validación para evitar eliminar categorías con suscripciones asociadas
- ✅ 10 categorías precargadas mediante seeder

### 🔄 Renovaciones
- ✅ **Historial completo** de renovaciones en tabla separada
- ✅ Registro de fecha anterior y nueva de vencimiento
- ✅ Registro de costo por ciclo
- ✅ Actualización automática de fecha de vencimiento al renovar
- ✅ Prevención de doble renovación (confirmación en formulario)

### 📧 Recordatorios por Correo
- ✅ **Comando Artisan** para envío automático: `php artisan suscripciones:recordatorios`
- ✅ **Modo simulación**: `php artisan suscripciones:recordatorios --simulate`
- ✅ Envío de correos al administrador con suscripciones por vencer y vencidas
- ✅ **Log de recordatorios** para evitar duplicados el mismo día
- ✅ Recordatorios configurables por suscripción (array de días: [15, 7, 3])
- ✅ Email HTML profesional con diseño responsive

### 🔍 Filtros y Búsqueda
- ✅ Búsqueda por nombre de servicio
- ✅ Filtro por categoría
- ✅ Filtro por periodicidad (mensual/anual)
- ✅ Filtro por estatus (activo/inactivo)
- ✅ Filtro por nivel de uso (bajo/medio/alto)
- ✅ Filtro por rango de costo (mínimo y máximo)

### 🔒 Seguridad
- ✅ **Rutas protegidas** con middleware `auth` y `role:admin`
- ✅ Solo usuarios con rol admin pueden acceder al módulo

---

## 🗄️ Estructura de Base de Datos

### Tabla: `categorias_suscripcion`
```sql
- idCategoria (PK)
- nombre (string, 100)
- estatus (enum: activo, inactivo)
- timestamps
```

### Tabla: `suscripciones`
```sql
- idSuscripcion (PK)
- idCategoria (FK → categorias_suscripcion)
- nombre_servicio (string, 150)
- fecha_inicio (date)
- costo (decimal 10,2)
- periodicidad (enum: mensual, anual)
- fecha_vencimiento (date, calculada automáticamente)
- dias_recordatorio (json, ej: [15, 7, 3])
- nivel_uso (enum: bajo, medio, alto)
- observaciones (text, nullable)
- estatus (enum: activo, inactivo)
- timestamps
```

### Tabla: `suscripcion_renovaciones`
```sql
- idRenovacion (PK)
- idSuscripcion (FK → suscripciones, cascade)
- fecha_renovacion (date)
- costo_ciclo (decimal 10,2)
- fecha_vencimiento_anterior (date)
- fecha_vencimiento_nueva (date)
- observaciones (text, nullable)
- timestamps
```

### Tabla: `suscripcion_recordatorios_log`
```sql
- id (PK)
- idSuscripcion (FK → suscripciones, cascade)
- fecha_recordatorio (date)
- dias_restantes (integer)
- timestamps
- UNIQUE(idSuscripcion, fecha_recordatorio)
```

---

## 🚀 Instalación y Configuración

### 1. Ejecutar Migraciones
```bash
php artisan migrate
```

### 2. Ejecutar Seeder de Categorías
```bash
php artisan db:seed --class=CategoriaSuscripcionSeeder
```

### 3. Configurar Correo (archivo .env)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_contraseña_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 4. Programar Comando de Recordatorios (Opcional)

Editar `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Ejecutar recordatorios diariamente a las 9:00 AM
    $schedule->command('suscripciones:recordatorios')
             ->dailyAt('09:00');
}
```

Luego configurar cron en el servidor:
```bash
* * * * * cd /ruta/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📝 Uso del Sistema

### Crear Nueva Suscripción
1. Ir a **Gestión de Suscripciones**
2. Clic en **"Nueva Suscripción"**
3. Llenar formulario:
   - Seleccionar categoría
   - Nombre del servicio
   - Fecha de inicio
   - Costo
   - Periodicidad (mensual/anual)
   - Días de recordatorio (opcional, ej: 15,7,3)
   - Nivel de uso
   - Observaciones (opcional)
4. La **fecha de vencimiento se calcula automáticamente**

### Renovar Suscripción
1. En la tabla, clic en el ícono de renovación (🔄)
2. Confirmar fecha de renovación (default: hoy)
3. Confirmar costo del ciclo (default: costo actual)
4. Agregar observaciones (opcional)
5. El sistema:
   - Crea registro en historial de renovaciones
   - Actualiza fecha de vencimiento al siguiente ciclo
   - Mantiene el historial completo

### Ver Historial de Renovaciones
1. Clic en el ícono de ver (👁️) en cualquier suscripción
2. Se muestra detalle completo + tabla de renovaciones históricas

### Dar de Baja / Reactivar
- **Dar de baja**: Cambia estatus a "inactivo" (no se elimina)
- **Reactivar**: Botón disponible en suscripciones inactivas

---

## 📧 Recordatorios Automáticos

### Comando Manual
```bash
# Enviar recordatorios reales
php artisan suscripciones:recordatorios

# Modo simulación (sin enviar correos)
php artisan suscripciones:recordatorios --simulate
```

### Lógica de Recordatorios
1. **Suscripciones vencidas**: Se envía recordatorio diario
2. **Suscripciones por vencer**: Se envía cuando `dias_restantes` coincide con `dias_recordatorio`
3. **Log automático**: Evita enviar el mismo recordatorio dos veces el mismo día
4. **Destinatario**: Primer usuario con rol `admin` en la BD

### Ejemplo de Configuración
Si una suscripción tiene `dias_recordatorio = [15, 7, 3]`:
- Se enviará recordatorio cuando falten 15 días
- Se enviará recordatorio cuando falten 7 días
- Se enviará recordatorio cuando falten 3 días
- Se enviará recordatorio diario si está vencida

---

## 🎨 Interfaz de Usuario

### Características de Diseño
- ✅ **Diseño moderno** con gradientes y efectos visuales
- ✅ **Responsive** con Tailwind CSS
- ✅ **Filtros avanzados** con múltiples criterios
- ✅ **Badges de estado** con colores semánticos:
  - 🟢 Verde: OK (más de 10 días)
  - 🟡 Amarillo: Por vencer (10 días o menos)
  - 🔴 Rojo: Vencida (días negativos)
- ✅ **Tabla completa** con toda la información relevante
- ✅ **Acciones rápidas**: Ver, Editar, Renovar

---

## 📊 Reglas de Negocio

### Cálculo de Fecha de Vencimiento
- **Mensual**: `fecha_inicio + 1 mes`
- **Anual**: `fecha_inicio + 1 año`
- Se calcula automáticamente al crear/editar

### Días Restantes
- Calculado dinámicamente: `fecha_vencimiento - hoy`
- No se guarda en BD (accessor en modelo)
- Puede ser negativo si está vencida

### Estados
- **OK**: `dias_restantes > 10`
- **Por vencer**: `0 < dias_restantes <= 10`
- **Vencida**: `dias_restantes < 0`

### Validaciones
- ✅ Costo >= 0
- ✅ Periodicidad válida (mensual/anual)
- ✅ Fecha de inicio requerida
- ✅ Categoría debe existir
- ✅ Días de recordatorio: array de enteros entre 1 y 365

---

## 📁 Archivos Principales

### Modelos
- `app/Models/CategoriaSuscripcion.php`
- `app/Models/Suscripcion.php`
- `app/Models/SuscripcionRenovacion.php`

### Controladores
- `app/Http/Controllers/CategoriaSuscripcionController.php`
- `app/Http/Controllers/SuscripcionController.php`

### FormRequests
- `app/Http/Requests/CategoriaSuscripcionRequest.php`
- `app/Http/Requests/SuscripcionRequest.php`
- `app/Http/Requests/RenovacionRequest.php`

### Migraciones
- `2026_01_19_215904_create_categorias_suscripcion_table.php`
- `2026_01_19_215906_create_suscripciones_table.php`
- `2026_01_19_215908_create_suscripcion_renovaciones_table.php`
- `2026_01_19_220659_create_suscripcion_recordatorios_log_table.php`

### Vistas
- `resources/views/suscripciones/index.blade.php`
- `resources/views/emails/recordatorio_suscripciones.blade.php`

### Comando Artisan
- `app/Console/Commands/EnviarRecordatoriosSuscripciones.php`

### Mailable
- `app/Mail/RecordatorioSuscripciones.php`

### Seeder
- `database/seeders/CategoriaSuscripcionSeeder.php`

### Rutas
- `routes/web.php` (líneas 151-166)

---

## 🔧 Mantenimiento

### Limpiar Log de Recordatorios (Opcional)
Si deseas limpiar recordatorios antiguos:
```sql
DELETE FROM suscripcion_recordatorios_log 
WHERE fecha_recordatorio < DATE_SUB(NOW(), INTERVAL 30 DAY);
```

### Backup de Datos
Asegúrate de incluir estas tablas en tus backups:
- `categorias_suscripcion`
- `suscripciones`
- `suscripcion_renovaciones`
- `suscripcion_recordatorios_log`

---

## 🎯 Categorías Precargadas

1. Hosting y Dominios
2. Software y Herramientas
3. Servicios en la Nube
4. Marketing Digital
5. Diseño y Creatividad
6. Comunicación y Colaboración
7. Seguridad y Respaldos
8. Bases de Datos
9. Licencias de Software
10. Otros

---

## 🚨 Troubleshooting

### El comando de recordatorios no envía correos
1. Verificar configuración de correo en `.env`
2. Probar con modo simulación: `--simulate`
3. Verificar que existe un usuario admin en la BD
4. Revisar logs: `storage/logs/laravel.log`

### Error al ejecutar migraciones
- Verificar que las migraciones anteriores estén ejecutadas
- Revisar que no existan tablas con el mismo nombre
- Usar `php artisan migrate:fresh` solo en desarrollo

### No aparecen las rutas
- Limpiar caché de rutas: `php artisan route:clear`
- Verificar middleware `role:admin`

---

## 📞 Soporte

Para dudas o problemas con el módulo, revisar:
1. Este README
2. Logs de Laravel: `storage/logs/laravel.log`
3. Código fuente documentado

---

## ✨ Características Destacadas

- ✅ **Código limpio y documentado**
- ✅ **Arquitectura MVC estándar de Laravel**
- ✅ **Validaciones robustas**
- ✅ **Seguridad con middleware**
- ✅ **UI moderna con Tailwind CSS**
- ✅ **Recordatorios inteligentes sin duplicados**
- ✅ **Historial completo de renovaciones**
- ✅ **Baja lógica (no destructiva)**
- ✅ **Filtros avanzados**
- ✅ **Responsive design**

---

**Versión:** 1.0  
**Fecha:** Enero 2026  
**Sistema:** Cliché SGI
