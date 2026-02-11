# Capítulo 5 – Pruebas e Implantación

## 1. Ambiente de Pruebas

En esta sección se describen las condiciones bajo las cuales se realizaron las pruebas del sistema **ClichéSGI** (Sistema de Gestión Integral para Cliché Marketing Digital).

### 1.1 Hardware

| Componente         | Especificación                                      |
|--------------------|-----------------------------------------------------|
| Equipo             | Laptop de desarrollo                                |
| Procesador         | Intel / AMD (compatible con Windows 10/11 64 bits)  |
| Memoria RAM        | 16 GB DDR4                                          |
| Almacenamiento     | 512 GB SSD                                          |
| Pantalla           | 15.6" Full HD (1920 × 1080)                         |
| Conectividad       | Wi-Fi 802.11ac, Ethernet                            |

### 1.2 Software

| Componente                | Versión / Detalle                                        |
|---------------------------|----------------------------------------------------------|
| Sistema Operativo         | Windows 10/11 (64 bits)                                  |
| Servidor Web              | WampServer 3.3.x (Apache 2.4)                           |
| Lenguaje Backend          | PHP 8.3.14                                               |
| Framework Backend         | Laravel 12.x                                             |
| Base de Datos             | MySQL 9.1.0                                              |
| Motor de almacenamiento   | MyISAM                                                   |
| Entorno de ejecución JS   | Node.js 22.17.0                                          |
| Gestor de paquetes        | Composer (PHP) / NPM (Node.js)                           |
| Framework Frontend        | Blade + Alpine.js + Tailwind CSS                         |
| Generación de PDF         | Barryvdh/DomPDF                                          |
| Navegador de pruebas      | Google Chrome (última versión estable)                   |
| IDE de desarrollo         | Visual Studio Code con extensión Windsurf                |
| Control de versiones      | Git                                                      |

### 1.3 Red

| Aspecto                   | Detalle                                                  |
|---------------------------|----------------------------------------------------------|
| Tipo de red               | Red local (localhost)                                    |
| Protocolo                 | HTTP (puerto 8000 vía `php artisan serve`)               |
| URL de acceso             | http://127.0.0.1:8000                                    |

### 1.4 Usuarios de Prueba

Las pruebas se realizaron con 5 usuarios registrados en el sistema, representando los tres roles disponibles. Se seleccionaron aquellos cuyos datos están relacionados entre sí en todas las tablas de la base de datos:

| # | Correo electrónico                         | Rol       | Descripción                              |
|---|--------------------------------------------|-----------|-----------------------------------------|
| 1 | luisd.m.c2002@gmail.com                    | admin     | Administrador del sistema                |
| 2 | luisd.m.c2002+E1@gmail.com                 | empleado  | Juan Carlos Pérez – Diseñador Gráfico   |
| 3 | luisd.m.c2002+E5@gmail.com                 | empleado  | Luis Fernando Hernández – Diseñador UX  |
| 4 | luisd.m.c2002+CALLOS@gmail.com             | cliente   | Los Callos de Cortes                     |
| 5 | luisd.m.c2002+CAMPESTRE@gmail.com          | cliente   | Campestre                                |

> Se realizaron pruebas con 1 usuario concurrente a la vez, dado que el ambiente de pruebas es local (localhost). Se validó el comportamiento del sistema desde los tres roles disponibles: **administrador**, **empleado** y **cliente**. Todos los datos de prueba en las tablas siguientes corresponden exclusivamente a estos 5 usuarios y sus registros relacionados.

---

## 2. Base de Datos de Pruebas

A continuación se presentan los datos utilizados en la base de datos durante la ejecución de las pruebas funcionales. Los datos fueron extraídos del respaldo generado el 10 de febrero de 2026 y filtrados para incluir **únicamente los registros relacionados con los 5 usuarios de prueba** seleccionados:

- **Administrador:** user_id = 1
- **Empleado 1:** Juan Carlos Pérez García (empleado_id = 1, user_id = 2)
- **Empleado 2:** Luis Fernando Hernández Silva (empleado_id = 5, user_id = 6)
- **Cliente 1:** LOS CALLOS DE CORTES (cliente_id = 2, user_id = 10)
- **Cliente 2:** CAMPESTRE (cliente_id = 7, user_id = 15)

### Tabla 1. Users (Usuarios del sistema)

| id | email | rol | created_at |
|----|-------|-----|------------|
| 1 | luisd.m.c2002@gmail.com | admin | 2026-01-26 |
| 2 | luisd.m.c2002+E1@gmail.com | empleado | 2024-07-27 |
| 6 | luisd.m.c2002+E5@gmail.com | empleado | 2025-05-27 |
| 10 | luisd.m.c2002+CALLOS@gmail.com | cliente | 2025-01-27 |
| 15 | luisd.m.c2002+CAMPESTRE@gmail.com | cliente | 2025-11-27 |

> **Nota:** Las contraseñas se almacenan cifradas con bcrypt y no se muestran por seguridad.

### Tabla 2. Empleados

| id | nombre | apellido_paterno | apellido_materno | teléfono | puesto | fecha_ingreso | estatus | user_id |
|----|--------|------------------|------------------|----------|--------|---------------|---------|---------|
| 1 | Juan Carlos | Pérez | García | 6141234567 | Diseñador Gráfico Senior | 2024-07-26 | activo | 2 |
| 5 | Luis Fernando | Hernández | Silva | 6145678901 | Diseñador UX/UI | 2025-05-26 | activo | 6 |

### Tabla 3. Clientes

| id | empresa | nombre | apellido_paterno | giro | teléfono | estatus | fecha_inicio | user_id |
|----|---------|--------|------------------|------|----------|---------|--------------|---------|
| 2 | LOS CALLOS DE CORTES | Carlos | Matsui | Restaurante Mariscos | 7771234502 | activo | 2024-02-02 | 10 |
| 7 | CAMPESTRE | Rosa | Rodríguez | Restaurante Mexicano | 7771234507 | activo | 2025-04-22 | 15 |

### Tabla 4. Información Fiscal de Clientes (info_fiscal)

| id | id_cliente | rfc | razon_social | domicilio_fiscal | regimen_fiscal | codigo_postal | correo_facturacion |
|----|------------|-----|--------------|------------------|----------------|---------------|--------------------|
| 1 | 2 | MACL850101AB1 | Carlos Matsui López S.A. de C.V. | Av. Juan Pablo II #45, Col. Vista Hermosa, Cuernavaca, Morelos | Régimen General de Ley Personas Morales | 62290 | facturacion@loscallosdecortes.com |

### Tabla 5. Cotizaciones

| id | id_cliente | titulo_cotizacion | fecha | vencimiento_dias | subtotal | iva | total | estatus |
|----|------------|-------------------|-------|------------------|----------|-----|-------|---------|
| 16 | 2 | Paquete Redes Sociales Premium | 2024-03-01 | 7 | $3,448.28 | $551.72 | $4,000.00 | aceptada |
| 21 | 7 | Paquete Campestre | 2025-05-01 | 7 | $2,715.52 | $434.48 | $3,150.00 | aceptada |

### Tabla 6. Detalle de Cotizaciones (cotizacion_detalle)

| id | id_cotizacion | orden | titulo | cantidad | descripción | precio_unitario | iva | total |
|----|---------------|-------|--------|----------|-------------|-----------------|-----|-------|
| 31 | 16 | 1 | Redes Sociales | 1 | Manejo de redes sociales premium | $1,724.14 | $275.86 | $2,000.00 |
| 32 | 16 | 2 | Fotografía | 1 | Sesión fotográfica mensual | $862.07 | $137.93 | $1,000.00 |
| 33 | 16 | 3 | Diseño | 1 | Diseño gráfico mensual | $862.07 | $137.93 | $1,000.00 |
| 38 | 21 | 1 | Redes Sociales | 1 | Manejo de redes sociales | $1,724.14 | $275.86 | $2,000.00 |
| 39 | 21 | 2 | Diseño | 1 | Diseño gráfico mensual | $991.38 | $158.62 | $1,150.00 |

### Tabla 7. Categorías de Tareas

| id | nombre | descripción | activo |
|----|--------|-------------|--------|
| 1 | Diseño Gráfico | Tareas de diseño visual y branding | 1 |
| 2 | Desarrollo Web | Programación y desarrollo web | 1 |
| 3 | Redes Sociales | Gestión de redes sociales | 1 |
| 4 | Marketing Digital | Estrategias de marketing | 1 |
| 5 | Fotografía y Video | Producción audiovisual | 1 |
| 6 | Redacción | Creación de contenido escrito | 1 |

> **Nota:** Las categorías son un catálogo general del sistema, no dependen de un usuario específico.

### Tabla 8. Tareas

| id | título | id_cliente | id_categoría | descripción | notas |
|----|--------|------------|--------------|-------------|-------|
| 21 | Diseño de menú digital | 2 | 1 | Crear menú digital interactivo para Los Callos de Cortes | Incluir fotos de platillos principales |
| 22 | Sesión fotográfica mariscos | 2 | 5 | Sesión de fotos profesional de platillos de mariscos | Coordinar con el chef para presentación |
| 27 | Fotografía de instalaciones | 7 | 5 | Sesión fotográfica del restaurante y jardines | Aprovechar luz natural de la mañana |

### Tabla 9. Asignaciones de Tareas (asignaciones_tareas)

| id | id_tarea | id_empleado | prioridad | fecha_entrega | estatus |
|----|----------|-------------|-----------|---------------|---------|
| 21 | 21 | 1 | alta | 2024-04-01 | terminada |
| 22 | 22 | 5 | alta | 2024-04-15 | terminada |
| 27 | 27 | 5 | media | 2025-06-01 | terminada |

### Tabla 10. Plataformas (Calendario de publicaciones)

| id | nombre | activo |
|----|--------|--------|
| 1 | Facebook | 1 |
| 2 | Instagram | 1 |
| 3 | X (Twitter) | 1 |
| 4 | LinkedIn | 1 |
| 5 | TikTok | 1 |
| 6 | YouTube | 1 |

> **Nota:** Las plataformas son un catálogo general del sistema.

### Tabla 11. Formatos (Calendario de publicaciones)

| id | nombre | descripción | activo |
|----|--------|-------------|--------|
| 1 | Post | Publicación estándar en feed | 1 |
| 2 | Story | Historia temporal de 24 horas | 1 |
| 3 | Reel | Video corto vertical | 1 |
| 4 | Carrusel | Múltiples imágenes deslizables | 1 |
| 5 | Video | Video largo en feed | 1 |
| 6 | Live | Transmisión en vivo | 1 |

> **Nota:** Los formatos son un catálogo general del sistema.

### Tabla 12. Publicaciones (Calendario de publicaciones)

| id | id_cliente | id_plataforma | id_formato | fecha | contenido | estatus |
|----|------------|---------------|------------|-------|-----------|---------|
| 26 | 2 | 1 (Facebook) | 1 (Post) | 2024-02-15 | Los mejores mariscos de Cuernavaca | Publicado |
| 27 | 2 | 2 (Instagram) | 3 (Reel) | 2024-04-10 | Torre de mariscos para compartir en familia | Publicado |
| 28 | 2 | 1 (Facebook) | 4 (Carrusel) | 2024-07-20 | Menú de verano – Ceviches y cocteles frescos | Publicado |
| 29 | 2 | 2 (Instagram) | 1 (Post) | 2024-11-25 | Reserva tu mesa para las fiestas decembrinas | Publicado |
| 30 | 2 | 1 (Facebook) | 1 (Post) | 2025-01-10 | Arrancamos el año con los mejores callos de hacha | Publicado |
| 60 | 2 | 1 (Facebook) | 1 (Post) | 2026-01-15 | Enero en Los Callos – Mariscos frescos del día | Pendiente |
| 45 | 7 | 1 (Facebook) | 1 (Post) | 2025-05-01 | Restaurante Campestre – Comida mexicana tradicional | Publicado |
| 46 | 7 | 2 (Instagram) | 3 (Reel) | 2025-06-20 | Barbacoa de hoyo los domingos | Publicado |
| 47 | 7 | 1 (Facebook) | 4 (Carrusel) | 2025-08-15 | Menú de fiestas patrias – Chiles en nogada | Publicado |
| 48 | 7 | 2 (Instagram) | 1 (Post) | 2025-11-01 | Ofrenda de Día de Muertos en Campestre | Publicado |

### Tabla 13. Minutas de Reuniones

| id | id_cliente | título | fecha | asistentes | temas_tratados | observaciones |
|----|------------|--------|-------|------------|----------------|---------------|
| 11 | 2 | Reunión inicial Los Callos de Cortes | 2024-01-20 | Carlos Matsui, Equipo Cliché | Definición de estrategia de redes sociales. Calendario de publicaciones. Sesiones fotográficas mensuales. | Cliente muy entusiasmado con el proyecto. Priorizar contenido de mariscos frescos. |
| 15 | 7 | Kickoff Campestre | 2025-04-28 | Rosa Rodríguez, Director Creativo | Análisis de competencia. Propuesta de contenido. Calendario mensual. | Destacar la ubicación y el ambiente familiar del restaurante. |

### Tabla 14. Acuerdos (derivados de minutas)

| id | id_minuta | descripción | responsable | orden | estatus | fecha_compromiso |
|----|-----------|-------------|-------------|-------|---------|-----------------|
| 12 | 11 | Enviar propuesta de menú digital | Equipo Cliché | 1 | completado | 2024-01-30 |
| 13 | 11 | Coordinar sesión fotográfica | Carlos Matsui | 2 | completado | 2024-02-15 |
| 20 | 15 | Recorrido por instalaciones | Director Creativo | 1 | completado | 2025-05-01 |
| 21 | 15 | Propuesta de contenido mensual | Equipo Cliché | 2 | completado | 2025-05-10 |

### Tabla 15. Categorías de Suscripciones

| id | nombre | estatus |
|----|--------|---------|
| 1 | Software de Diseño | activo |
| 2 | Hosting y Dominios | activo |
| 3 | Herramientas de Marketing | activo |
| 4 | Almacenamiento en la Nube | activo |
| 5 | Comunicación | activo |
| 6 | Gestión de Proyectos | activo |
| 7 | Inteligencia Artificial | activo |

> **Nota:** Las categorías de suscripciones son un catálogo general de la empresa.

### Tabla 16. Suscripciones

| id | id_categoría | nombre | fecha_inicio | costo | periodicidad | próx_renovación | prioridad | descripción | estatus |
|----|--------------|--------|--------------|-------|--------------|-----------------|-----------|-------------|---------|
| 1 | 1 | Adobe Creative Cloud | 2024-03-15 | $1,099.00 | mensual | 2026-02-15 | alto | Plan completo: Photoshop, Illustrator, Premiere Pro | activo |
| 5 | 2 | Hostinger Business | 2023-08-01 | $2,999.00 | anual | 2026-08-01 | alto | Hosting para sitios web de clientes | activo |
| 9 | 4 | Google Workspace Business | 2023-05-01 | $288.00 | anual | 2026-05-01 | alto | Gmail, Drive 2TB, Meet para el equipo | activo |
| 15 | 7 | ChatGPT Plus | 2024-02-01 | $20.00 | mensual | 2026-02-01 | alto | GPT-4 para generación de contenido y código | activo |

> **Nota:** Las suscripciones son recursos de la empresa, no específicos de un usuario. Se incluye una muestra representativa de 4 suscripciones que cubren diferentes categorías y periodicidades.

### Tabla 17. Renovaciones de Suscripciones (suscripcion_renovaciones)

| id | id_suscripcion | fecha_renovación | monto | periodo_inicio | periodo_fin | notas |
|----|----------------|------------------|-------|----------------|-------------|-------|
| 1 | 1 | 2024-12-15 | $1,099.00 | 2024-12-15 | 2025-01-15 | Renovación automática |
| 2 | 1 | 2025-01-15 | $1,099.00 | 2025-01-15 | 2026-02-15 | Renovación automática |
| 6 | 5 | 2024-08-01 | $2,799.00 | 2024-08-01 | 2025-08-01 | Renovación con promoción |
| 7 | 5 | 2025-08-01 | $2,999.00 | 2025-08-01 | 2026-08-01 | Precio regular |
| 8 | 9 | 2024-05-01 | $264.00 | 2024-05-01 | 2025-05-01 | Renovación anual |
| 9 | 9 | 2025-05-01 | $288.00 | 2025-05-01 | 2026-05-01 | Aumento de precio |

### Tabla 18. Briefs (Formularios para clientes)

| id | título | descripción (resumen) | form_url |
|----|--------|-----------------------|----------|
| 4 | Brief Inicial | ¡Bienvenido a nuestra agencia de marketing! Nos emociona comenzar este viaje juntos... | https://docs.google.com/forms/d/e/... |
| 7 | Formulario de Información para Sesión de Fotos | Recopilación de detalles esenciales para planificar tu próxima sesión fotográfica. | https://docs.google.com/forms/d/e/... |

> **Nota:** Los briefs son plantillas de formularios generales. Durante las pruebas de **FN13** y **FN16** se asignan briefs a los clientes de prueba seleccionados.

### Tabla 19. Brief-Cliente (Asignaciones de briefs a clientes)

| id | brief_id | cliente_id | estado | fecha_envio | fecha_ultimo_recordatorio |
|----|----------|------------|--------|-------------|---------------------------|
| 1 | 4 | 2 | pendiente | 2026-01-20 | 2026-02-01 |
| 2 | 7 | 7 | recibido | 2026-01-25 | — |

### Tabla 20. Eventos (Agenda)

| id | título | id_cliente | id_empleado | fecha | hora_inicio | hora_fin | ubicación | descripción | color | recurrencia |
|----|--------|------------|-------------|-------|-------------|----------|-----------|-------------|-------|-------------|
| 16 | Sesión fotos Los Callos | 2 | 1 | 2024-03-15 | 10:00 | 13:00 | Juan Pablo II Col Vista Hermosa | Sesión fotográfica de platillos de mariscos | #3B82F6 | ninguna |
| 21 | Visita Campestre | 7 | 1 | 2025-05-15 | 10:00 | 13:00 | Carretera México-Cuernavaca KM 55 Huitzilac | Recorrido y sesión fotográfica | #10B981 | ninguna |
| 26 | Reunión planificación 2026 | 2 | 1 | 2026-01-20 | 10:00 | 12:00 | Juan Pablo II Col Vista Hermosa | Planificación anual de contenido | #3B82F6 | ninguna |

### Tabla 21. Bitácora de Clientes (bitacora_clientes)

| id | id_cliente | tipo_evento | id_usuario | fecha |
|----|------------|-------------|------------|-------|
| 62 | 2 | alta | 1 | 2024-02-02 |
| 67 | 7 | alta | 1 | 2025-04-22 |
| 76 | 2 | baja | 1 | 2024-08-15 |
| 77 | 2 | reactivación | 1 | 2024-10-01 |

> **Nota:** La tabla `bitacora_clientes` registra automáticamente los eventos de alta, baja y reactivación de clientes. El cliente **Los Callos de Cortes** cuenta con un historial de baja y reactivación que permite validar el **Reporte de crecimiento de clientes (FN22)**.

### Tabla 22. Notificaciones

| id | user_id | tarea_id | tipo | titulo | mensaje |
|----|---------|----------|------|--------|---------|
| 1 | 2 | 1 | tarea_asignada | Nueva tarea asignada | Se te ha asignado la tarea: Diseño del sistema |

> **Nota:** Las notificaciones se generan automáticamente por el sistema al asignar tareas, evaluar entregas y enviar briefs. Se incluye un registro de ejemplo; durante las pruebas se generan notificaciones adicionales al ejecutar las operaciones correspondientes.

---

### Relación de tablas con requisitos funcionales

| Tabla(s) | Requisito(s) funcional(es) que soporta |
|----------|----------------------------------------|
| users | FN1 – Inicio de sesión |
| empleados | FN2 – Gestión de empleados |
| clientes, bitacora_clientes | FN3 – Gestión de clientes, FN22 – Reporte de crecimiento |
| info_fiscal | FN4 – Información fiscal de clientes |
| cotizaciones, cotizacion_detalle | FN5 – Gestión de cotizaciones, FN18 – Reporte de efectividad e ingresos |
| categorias | FN7 – Catálogo de clasificación de tareas |
| tareas, asignaciones_tareas | FN6, FN8, FN14 – Gestión de tareas, asignaciones y seguimiento, FN20 – Reporte de carga de trabajo |
| plataformas, formatos, publicaciones | FN9 – Calendario de publicaciones |
| minutas | FN10 – Minuta de reuniones |
| acuerdos | FN11 – Gestión de acuerdos, FN21 – Reporte de acuerdos |
| categorias_suscripcion, suscripciones, suscripcion_renovaciones | FN12 – Control de suscripciones, FN19 – Reporte de costos y proyección |
| briefs, brief_cliente | FN13 – Briefs para clientes (Formulario), FN16 – Briefs para clientes |
| eventos | FN15 – Agenda |
| notificaciones | FN6, FN8, FN13, FN16 – Notificaciones automáticas del sistema |
| (Reportes generados con datos de múltiples tablas) | FN17 – Reporte de cumplimiento, FN18, FN19, FN20, FN21, FN22 |
| (Funcionalidad del BackupController) | FN23 – Respaldo y recuperación de base de datos |
