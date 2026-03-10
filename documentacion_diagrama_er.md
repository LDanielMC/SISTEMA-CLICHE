# 📊 DOCUMENTACIÓN DEL DIAGRAMA ENTIDAD-RELACIÓN
## Sistema Cliché Marketing Digital

---

## 🎯 RESUMEN EJECUTIVO

**Total de tablas en la base de datos:** 30 tablas  
**Tablas activas en el sistema:** 24 tablas  
**Tablas técnicas (Laravel/caché):** 6 tablas  
**Relaciones físicas (Foreign Keys):** 23 relaciones  
**Relaciones lógicas:** 3 relaciones  

---

## ✅ TABLAS ACTIVAS DEL SISTEMA (24)

Estas tablas contienen datos operativos y se utilizan activamente:

### **MÓDULO: AUTENTICACIÓN Y USUARIOS**
1. **`users`** - Usuarios del sistema (admin, empleados, clientes)

### **MÓDULO: RECURSOS HUMANOS**
2. **`empleados`** - Información de empleados de la agencia

### **MÓDULO: CLIENTES**
3. **`clientes`** - Clientes de la agencia
4. **`info_fiscal`** - Información fiscal para facturación
5. **`bitacora_clientes`** - Registro de movimientos (alta/baja/reactivación)

### **MÓDULO: COTIZACIONES**
6. **`cotizaciones`** - Cotizaciones emitidas a clientes
7. **`cotizacion_detalle`** - Partidas/servicios de cotizaciones

### **MÓDULO: GESTIÓN DE TAREAS**
8. **`categorias`** - Categorías de tareas (Diseño, Desarrollo, etc.)
9. **`tareas`** - Tareas maestras del sistema
10. **`asignaciones_tareas`** - Asignaciones de tareas a empleados

### **MÓDULO: NOTIFICACIONES**
11. **`notificaciones`** - Notificaciones del sistema

### **MÓDULO: SUSCRIPCIONES**
12. **`categorias_suscripcion`** - Categorías de suscripciones
13. **`suscripciones`** - Suscripciones de servicios (Adobe, Canva, etc.)
14. **`suscripcion_renovaciones`** - Historial de renovaciones
15. **`suscripcion_recordatorios_log`** - Log de recordatorios enviados

### **MÓDULO: MINUTAS Y ACUERDOS**
16. **`minutas`** - Minutas de reuniones con clientes
17. **`acuerdos`** - Acuerdos derivados de minutas

### **MÓDULO: BRIEFS**
18. **`briefs`** - Formularios de briefs (Google Forms)
19. **`brief_cliente`** - Asignación de briefs a clientes

### **MÓDULO: EVENTOS Y CALENDARIO**
20. **`eventos`** - Eventos del calendario
21. **`evento_participantes`** - Participantes de eventos
22. **`evento_recordatorios`** - Recordatorios de eventos

### **MÓDULO: PUBLICACIONES**
23. **`plataformas`** - Plataformas de redes sociales
24. **`formatos`** - Formatos de publicaciones
25. **`publicaciones`** - Publicaciones programadas

---

## ⚙️ TABLAS TÉCNICAS DE LARAVEL (6)

Estas tablas NO se incluyen en el diagrama ER porque son soporte técnico:

1. **`migrations`** - Control de versiones de BD (Laravel)
2. **`cache`** - Sistema de caché temporal
3. **`cache_locks`** - Bloqueos de caché
4. **`sessions`** - Sesiones de usuarios
5. **`failed_jobs`** - Cola de trabajos fallidos
6. **`jobs`** - Cola de trabajos pendientes
7. **`job_batches`** - Lotes de trabajos
8. **`password_reset_tokens`** - Tokens de recuperación
9. **`personal_access_tokens`** - Tokens de API

---

## 🔗 RELACIONES FÍSICAS (FOREIGN KEYS)

### **users → empleados (1:1)**
- **Tabla origen:** `empleados`
- **FK:** `id_usuario` → `users(id)`
- **Tipo:** Relación fuerte (RESTRICT)
- **Cardinalidad:** Un usuario puede ser un empleado
- **Descripción:** Cada empleado tiene una cuenta de usuario asociada

### **users → clientes (1:1)**
- **Tabla origen:** `clientes`
- **FK:** `id_usuario` → `users(id)`
- **Tipo:** Relación fuerte (RESTRICT)
- **Cardinalidad:** Un usuario puede ser un cliente
- **Descripción:** Cada cliente tiene una cuenta de usuario para acceder al portal

### **clientes → info_fiscal (1:N)**
- **Tabla origen:** `info_fiscal`
- **FK:** `id_cliente` → `clientes(id_cliente)`
- **Tipo:** Relación fuerte (CASCADE)
- **Cardinalidad:** Un cliente puede tener múltiples registros fiscales
- **Descripción:** Información fiscal para facturación

### **clientes → bitacora_clientes (1:N)**
- **Tabla origen:** `bitacora_clientes`
- **FK:** `id_cliente` → `clientes(id_cliente)`
- **Tipo:** Relación fuerte (CASCADE)
- **Cardinalidad:** Un cliente tiene múltiples movimientos en bitácora
- **Descripción:** Registro histórico de altas, bajas y reactivaciones

### **users → bitacora_clientes (1:N)**
- **Tabla origen:** `bitacora_clientes`
- **FK:** `id_usuario_responsable` → `users(id)`
- **Tipo:** Relación débil (SET NULL)
- **Cardinalidad:** Un usuario puede haber registrado múltiples movimientos
- **Descripción:** Usuario administrador que realizó el movimiento

### **clientes → cotizaciones (1:N)**
- **Tabla origen:** `cotizaciones`
- **FK:** `id_cliente` → `clientes(id_cliente)`
- **Tipo:** Relación fuerte (RESTRICT)
- **Cardinalidad:** Un cliente puede tener múltiples cotizaciones
- **Descripción:** Cotizaciones emitidas al cliente

### **cotizaciones → cotizacion_detalle (1:N)**
- **Tabla origen:** `cotizacion_detalle`
- **FK:** `id_cotizacion` → `cotizaciones(id_cotizacion)`
- **Tipo:** Relación fuerte (CASCADE)
- **Cardinalidad:** Una cotización tiene múltiples partidas
- **Descripción:** Detalles/servicios de la cotización

### **clientes → tareas (1:N)**
- **Tabla origen:** `tareas`
- **FK:** `cliente_id` → `clientes(id_cliente)`
- **Tipo:** Relación fuerte (CASCADE)
- **Cardinalidad:** Un cliente tiene múltiples tareas
- **Descripción:** Tareas asociadas al cliente

### **categorias → tareas (1:N)**
- **Tabla origen:** `tareas`
- **FK:** `categoria_id` → `categorias(id)`
- **Tipo:** Relación fuerte (RESTRICT)
- **Cardinalidad:** Una categoría agrupa múltiples tareas
- **Descripción:** Clasificación de tareas (Diseño, Desarrollo, etc.)

### **tareas → asignaciones_tareas (1:N)**
- **Tabla origen:** `asignaciones_tareas`
- **FK:** `tarea_id` → `tareas(id)`
- **Tipo:** Relación fuerte (CASCADE)
- **Cardinalidad:** Una tarea puede asignarse múltiples veces
- **Descripción:** Permite reasignar la misma tarea a diferentes empleados

### **empleados → asignaciones_tareas (1:N)**
- **Tabla origen:** `asignaciones_tareas`
- **FK:** `empleado_id` → `empleados(id_empleado)`
- **Tipo:** Relación fuerte (CASCADE)
- **Cardinalidad:** Un empleado tiene múltiples asignaciones
- **Descripción:** Tareas asignadas al empleado

### **users → notificaciones (1:N)**
- **Tabla origen:** `notificaciones`
- **FK:** `user_id` → `users(id)`
- **Tipo:** Relación fuerte (CASCADE)
- **Cardinalidad:** Un usuario recibe múltiples notificaciones
- **Descripción:** Notificaciones del sistema

### **asignaciones_tareas → notificaciones (1:N)**
- **Tabla origen:** `notificaciones`
- **FK:** `asignacion_tarea_id` → `asignaciones_tareas(id)`
- **Tipo:** Relación débil (CASCADE, nullable)
- **Cardinalidad:** Una asignación puede generar múltiples notificaciones
- **Descripción:** Notificaciones relacionadas con tareas

### **categorias_suscripcion → suscripciones (1:N)**
- **Tabla origen:** `suscripciones`
- **FK:** `idCategoria` → `categorias_suscripcion(idCategoria)`
- **Tipo:** Relación fuerte (RESTRICT)
- **Cardinalidad:** Una categoría agrupa múltiples suscripciones
- **Descripción:** Clasificación de suscripciones (Software, Hosting, etc.)

### **suscripciones → suscripcion_renovaciones (1:N)**
- **Tabla origen:** `suscripcion_renovaciones`
- **FK:** `idSuscripcion` → `suscripciones(idSuscripcion)`
- **Tipo:** Relación fuerte (CASCADE)
- **Cardinalidad:** Una suscripción tiene múltiples renovaciones
- **Descripción:** Historial de renovaciones

### **suscripciones → suscripcion_recordatorios_log (1:N)**
- **Tabla origen:** `suscripcion_recordatorios_log`
- **FK:** `idSuscripcion` → `suscripciones(idSuscripcion)`
- **Tipo:** Relación fuerte (CASCADE)
- **Cardinalidad:** Una suscripción tiene múltiples recordatorios
- **Descripción:** Log de recordatorios enviados

### **clientes → minutas (1:N)**
- **Tabla origen:** `minutas`
- **FK:** `id_cliente` → `clientes(id_cliente)`
- **Tipo:** Relación fuerte (CASCADE)
- **Cardinalidad:** Un cliente tiene múltiples minutas
- **Descripción:** Minutas de reuniones

### **minutas → acuerdos (1:N)**
- **Tabla origen:** `acuerdos`
- **FK:** `id_minuta` → `minutas(id_minuta)`
- **Tipo:** Relación fuerte (CASCADE)
- **Cardinalidad:** Una minuta tiene múltiples acuerdos
- **Descripción:** Acuerdos derivados de la reunión

### **briefs → brief_cliente (1:N)**
- **Tabla origen:** `brief_cliente`
- **FK:** `brief_id` → `briefs(id)`
- **Tipo:** Relación fuerte (CASCADE)
- **Cardinalidad:** Un brief se asigna a múltiples clientes
- **Descripción:** Relación many-to-many entre briefs y clientes

### **clientes → brief_cliente (1:N)**
- **Tabla origen:** `brief_cliente`
- **FK:** `cliente_id` → `clientes(id_cliente)`
- **Tipo:** Relación fuerte (CASCADE)
- **Cardinalidad:** Un cliente puede tener múltiples briefs
- **Descripción:** Briefs asignados al cliente

### **users → eventos (1:N)**
- **Tabla origen:** `eventos`
- **FK:** `creado_por` → `users(id)`
- **Tipo:** Relación fuerte (RESTRICT)
- **Cardinalidad:** Un usuario crea múltiples eventos
- **Descripción:** Usuario que creó el evento

### **clientes → eventos (1:N)**
- **Tabla origen:** `eventos`
- **FK:** `cliente_id` → `clientes(id_cliente)`
- **Tipo:** Relación débil (SET NULL, nullable)
- **Cardinalidad:** Un cliente tiene múltiples eventos
- **Descripción:** Cliente relacionado con el evento

### **eventos → evento_participantes (1:N)**
- **Tabla origen:** `evento_participantes`
- **FK:** `evento_id` → `eventos(id)`
- **Tipo:** Relación fuerte (CASCADE)
- **Cardinalidad:** Un evento tiene múltiples participantes
- **Descripción:** Asistentes al evento

### **eventos → evento_recordatorios (1:N)**
- **Tabla origen:** `evento_recordatorios`
- **FK:** `evento_id` → `eventos(id)`
- **Tipo:** Relación fuerte (CASCADE)
- **Cardinalidad:** Un evento tiene múltiples recordatorios
- **Descripción:** Recordatorios configurados

### **clientes → publicaciones (1:N)**
- **Tabla origen:** `publicaciones`
- **FK:** `cliente_id` → `clientes(id_cliente)`
- **Tipo:** Relación fuerte (CASCADE)
- **Cardinalidad:** Un cliente tiene múltiples publicaciones
- **Descripción:** Contenido para redes sociales

### **plataformas → publicaciones (1:N)**
- **Tabla origen:** `publicaciones`
- **FK:** `plataforma_id` → `plataformas(id)`
- **Tipo:** Relación fuerte (RESTRICT)
- **Cardinalidad:** Una plataforma tiene múltiples publicaciones
- **Descripción:** Red social destino

### **formatos → publicaciones (1:N)**
- **Tabla origen:** `publicaciones`
- **FK:** `formato_id` → `formatos(id)`
- **Tipo:** Relación fuerte (RESTRICT)
- **Cardinalidad:** Un formato se usa en múltiples publicaciones
- **Descripción:** Tipo de contenido (Post, Story, Reel, etc.)

---

## 🔄 RELACIONES LÓGICAS (SIN FK FÍSICA)

### **evento_participantes → clientes/empleados (Polimórfica)**
- **Campos:** `tipo` (ENUM), `referencia_id` (BIGINT)
- **Descripción:** Un participante puede ser cliente, empleado o externo
- **Razón sin FK:** Relación polimórfica que apunta a diferentes tablas según el tipo

### **publicaciones - Agrupación por lote**
- **Campo:** `lote_calendario` (UUID)
- **Descripción:** Agrupa publicaciones creadas en el mismo calendario mensual
- **Razón sin FK:** Es un identificador de agrupación lógica, no una entidad

### **briefs - Integración con Google Forms**
- **Campo:** `google_form_id`, `google_response_id`
- **Descripción:** Integración con API de Google Forms
- **Razón sin FK:** Son referencias a sistema externo

---

## 📊 CAMPOS IMPORTANTES POR MÓDULO

### **USUARIOS Y AUTENTICACIÓN**
```sql
users
├── id (PK, BIGINT UNSIGNED, NOT NULL, AUTO_INCREMENT)
├── email (VARCHAR(191), NOT NULL, UNIQUE)
├── password (VARCHAR(191), NOT NULL)
├── rol (ENUM: admin, empleado, cliente, NOT NULL, DEFAULT 'cliente')
└── timestamps
```

### **EMPLEADOS**
```sql
empleados
├── id_empleado (PK, BIGINT UNSIGNED, NOT NULL, AUTO_INCREMENT)
├── nombre (VARCHAR(120), NOT NULL)
├── apellido_paterno (VARCHAR(120), NOT NULL)
├── apellido_materno (VARCHAR(120), NULL)
├── telefono (VARCHAR(30), NOT NULL)
├── puesto (VARCHAR(120), NOT NULL)
├── fecha_ingreso (DATE, NOT NULL)
├── estatus (ENUM: activo, inactivo, baja, NOT NULL, DEFAULT 'activo')
├── fecha_baja (DATE, NULL)
├── id_usuario (FK → users, BIGINT UNSIGNED, NOT NULL)
└── timestamps
```

### **CLIENTES**
```sql
clientes
├── id_cliente (PK, BIGINT UNSIGNED, NOT NULL, AUTO_INCREMENT)
├── empresa (VARCHAR(180), NULL)
├── nombre (VARCHAR(120), NOT NULL)
├── apellido_paterno (VARCHAR(120), NOT NULL)
├── apellido_materno (VARCHAR(120), NULL)
├── giro_sector (VARCHAR(150), NOT NULL)
├── telefono (VARCHAR(30), NOT NULL)
├── estatus (ENUM: activo, inactivo, NOT NULL, DEFAULT 'activo')
├── fecha_registro (DATE, NOT NULL)
├── fecha_baja (DATE, NULL)
├── id_usuario (FK → users, BIGINT UNSIGNED, NOT NULL)
└── timestamps
```

### **COTIZACIONES**
```sql
cotizaciones
├── id_cotizacion (PK, BIGINT UNSIGNED, NOT NULL, AUTO_INCREMENT)
├── id_cliente (FK → clientes, BIGINT UNSIGNED, NOT NULL)
├── titulo_cotizacion (VARCHAR(200), NOT NULL)
├── texto_introduccion (TEXT, NULL)
├── fecha (DATE, NOT NULL)
├── vencimiento_dias (TINYINT UNSIGNED, NOT NULL, DEFAULT 7)
├── subtotal (DECIMAL(12,2), NOT NULL, DEFAULT 0.00)
├── iva_total (DECIMAL(12,2), NOT NULL, DEFAULT 0.00)
├── porcentaje_isr (DECIMAL(5,2), NOT NULL, DEFAULT 0.00)
├── retencion_isr (DECIMAL(15,2), NOT NULL, DEFAULT 0.00)
├── total (DECIMAL(12,2), NOT NULL, DEFAULT 0.00)
├── notas (TEXT, NULL)
├── estatus (ENUM: pendiente, aceptada, rechazada, NOT NULL, DEFAULT 'pendiente')
└── timestamps
```

### **TAREAS Y ASIGNACIONES**
```sql
tareas
├── id (PK, BIGINT UNSIGNED, NOT NULL, AUTO_INCREMENT)
├── titulo (VARCHAR(200), NOT NULL)
├── cliente_id (FK → clientes, BIGINT UNSIGNED, NOT NULL)
├── categoria_id (FK → categorias, BIGINT UNSIGNED, NOT NULL)
├── descripcion (TEXT, NOT NULL)
├── observaciones (TEXT, NULL)
└── timestamps

asignaciones_tareas
├── id (PK, BIGINT UNSIGNED, NOT NULL, AUTO_INCREMENT)
├── tarea_id (FK → tareas, BIGINT UNSIGNED, NOT NULL)
├── empleado_id (FK → empleados, BIGINT UNSIGNED, NOT NULL)
├── prioridad (ENUM: baja, media, alta, urgente, NOT NULL, DEFAULT 'media')
├── fecha_limite (DATE, NOT NULL)
├── estado_empleado (ENUM: asignada, en_proceso, terminada, NOT NULL, DEFAULT 'asignada')
├── estado_admin (ENUM: pendiente, completa, parcialmente_completa, incompleta, NULL)
├── evidencia_path (VARCHAR(191), NULL)
├── fecha_entrega (TIMESTAMP, NULL)
├── notas_admin (TEXT, NULL)
└── timestamps
```

### **SUSCRIPCIONES**
```sql
suscripciones
├── idSuscripcion (PK, BIGINT UNSIGNED, NOT NULL, AUTO_INCREMENT)
├── idCategoria (FK → categorias_suscripcion, BIGINT UNSIGNED, NOT NULL)
├── nombre_servicio (VARCHAR(150), NOT NULL)
├── fecha_inicio (DATE, NOT NULL)
├── costo (DECIMAL(10,2), NOT NULL)
├── periodicidad (ENUM: mensual, anual, NOT NULL)
├── fecha_vencimiento (DATE, NOT NULL)
├── dias_recordatorio (JSON, NULL)
├── nivel_uso (ENUM: bajo, medio, alto, NOT NULL, DEFAULT 'medio')
├── observaciones (TEXT, NULL)
├── estatus (ENUM: activo, inactivo, NOT NULL, DEFAULT 'activo')
└── timestamps
```

### **MINUTAS Y ACUERDOS**
```sql
minutas
├── id_minuta (PK, BIGINT UNSIGNED, NOT NULL, AUTO_INCREMENT)
├── id_cliente (FK → clientes, BIGINT UNSIGNED, NOT NULL)
├── titulo (VARCHAR(255), NULL)
├── fecha (DATE, NOT NULL)
├── asistentes (VARCHAR(500), NULL) -- HTML
├── puntos_tratados (LONGTEXT, NULL) -- HTML
├── observaciones (LONGTEXT, NULL) -- HTML
└── timestamps

acuerdos
├── id_acuerdo (PK, BIGINT UNSIGNED, NOT NULL, AUTO_INCREMENT)
├── id_minuta (FK → minutas, BIGINT UNSIGNED, NOT NULL)
├── acuerdo (LONGTEXT, NOT NULL)
├── responsable (VARCHAR(255), NULL)
├── orden (INT, NOT NULL, DEFAULT 0)
├── estatus (ENUM: pendiente, completado, cancelado, NOT NULL, DEFAULT 'pendiente')
├── fecha_limite (DATE, NULL)
└── timestamps
```

### **EVENTOS**
```sql
eventos
├── id (PK, BIGINT UNSIGNED, NOT NULL, AUTO_INCREMENT)
├── titulo (VARCHAR(191), NOT NULL)
├── cliente_id (FK → clientes, BIGINT UNSIGNED, NULL)
├── creado_por (FK → users, BIGINT UNSIGNED, NOT NULL)
├── fecha (DATE, NOT NULL)
├── hora_inicio (TIME, NOT NULL)
├── hora_fin (TIME, NOT NULL)
├── lugar (VARCHAR(191), NULL)
├── notas (TEXT, NULL)
├── color (VARCHAR(191), NOT NULL, DEFAULT '#3B82F6')
├── recurrencia (ENUM: ninguna, diaria, semanal, mensual, anual, NOT NULL, DEFAULT 'ninguna')
├── recurrencia_config (JSON, NULL)
├── recurrencia_hasta (DATE, NULL)
├── google_event_id (VARCHAR(191), NULL, UNIQUE)
├── sincronizado_google (TINYINT(1), NOT NULL, DEFAULT 0)
├── ultima_sincronizacion (TIMESTAMP, NULL)
└── timestamps
```

### **PUBLICACIONES**
```sql
publicaciones
├── idPublicacion (PK, BIGINT UNSIGNED, NOT NULL, AUTO_INCREMENT)
├── cliente_id (FK → clientes, BIGINT UNSIGNED, NOT NULL)
├── lote_calendario (VARCHAR(36), NULL) -- UUID
├── plataforma_id (FK → plataformas, BIGINT UNSIGNED, NOT NULL)
├── formato_id (FK → formatos, BIGINT UNSIGNED, NOT NULL)
├── fecha (DATE, NOT NULL)
├── copy (TEXT, NULL)
├── arte (TEXT, NULL)
├── estatus (ENUM: Pendiente, Publicado, Reprogramar, NOT NULL, DEFAULT 'Pendiente')
└── timestamps
```

---

## 🎨 CÓMO USAR EL SCRIPT EN MYSQL WORKBENCH

### **Opción 1: Ingeniería Inversa (Recomendado)**
1. Abrir MySQL Workbench
2. Conectar a tu servidor MySQL
3. Ir a: **Database** → **Reverse Engineer**
4. Seleccionar la base de datos `cliche`
5. Seguir el asistente
6. El diagrama se generará automáticamente con todas las relaciones

### **Opción 2: Ejecutar Script**
1. Abrir MySQL Workbench
2. Ir a: **File** → **Open SQL Script**
3. Seleccionar: `diagrama_er_cliche.sql`
4. Ejecutar el script
5. Ir a: **Database** → **Reverse Engineer**
6. Generar diagrama

### **Opción 3: Modelo EER Manual**
1. Abrir MySQL Workbench
2. Ir a: **File** → **New Model**
3. Copiar y pegar las definiciones de tablas
4. Agregar manualmente las relaciones visuales

---

## 📈 ESTADÍSTICAS DEL DIAGRAMA

| Concepto | Cantidad |
|----------|----------|
| **Total de tablas** | 24 (activas) |
| **Relaciones FK** | 26 |
| **Tablas maestras** | 9 (users, clientes, empleados, categorias, etc.) |
| **Tablas de detalle** | 8 (cotizacion_detalle, acuerdos, etc.) |
| **Tablas pivot** | 3 (asignaciones_tareas, brief_cliente, evento_participantes) |
| **Tablas de bitácora** | 4 (bitacora_clientes, renovaciones, recordatorios_log, notificaciones) |
| **Campos NOT NULL** | ~80% de campos |
| **Campos con DEFAULT** | ~40% de campos |
| **Índices** | 35+ índices |

---

## 🔒 TIPOS DE RELACIONES DE INTEGRIDAD

### **CASCADE (Cascada)**
- Cuando se elimina el padre, se eliminan los hijos
- Usado en: cotizacion_detalle, acuerdos, asignaciones_tareas, etc.

### **RESTRICT (Restringir)**
- No permite eliminar el padre si tiene hijos
- Usado en: users → empleados, categorias → tareas

### **SET NULL (Anular)**
- Cuando se elimina el padre, el FK del hijo se pone NULL
- Usado en: clientes → eventos, users → bitacora_clientes

---

## 📝 NOTAS IMPORTANTES

1. **Campos HTML:** Las minutas usan editores de texto enriquecido (Quill), por lo que almacenan HTML en los campos
2. **Archivos:** Las evidencias de tareas se almacenan en `storage/app/evidencias/`
3. **Soft Deletes:** El sistema NO usa soft deletes de Laravel, usa campos de estatus
4. **Timestamps:** Todas las tablas tienen `created_at` y `updated_at`
5. **UUIDs:** Solo se usan para `lote_calendario` en publicaciones
6. **JSON:** Se usa para `dias_recordatorio` en suscripciones y `recurrencia_config` en eventos

---

**Generado:** 27 de febrero de 2026  
**Versión:** 1.0  
**Base de datos:** `cliche`  
**Motor:** MySQL 9.1.0 / InnoDB
