-- Script para poblar la base de datos Cliché con datos de prueba
-- Mantiene el usuario admin existente

SET FOREIGN_KEY_CHECKS=0;

-- Limpiar datos (excepto admin)
DELETE FROM publicaciones;
DELETE FROM formatos;
DELETE FROM plataformas;
DELETE FROM evento_participantes;
DELETE FROM evento_recordatorios;
DELETE FROM eventos;
DELETE FROM suscripcion_renovaciones;
DELETE FROM suscripcion_recordatorios_log;
DELETE FROM suscripciones;
DELETE FROM categorias_suscripcion;
DELETE FROM acuerdos;
DELETE FROM minutas;
DELETE FROM cotizacion_detalle;
DELETE FROM cotizaciones;
DELETE FROM asignaciones_tareas;
DELETE FROM tareas;
DELETE FROM categorias;
DELETE FROM clientes;
DELETE FROM empleados;
DELETE FROM users WHERE id != 1;

SET FOREIGN_KEY_CHECKS=1;

-- ============================================
-- USUARIOS (7 empleados + 7 clientes)
-- ============================================

INSERT INTO users (email, password, rol, email_verified_at, created_at, updated_at) VALUES
-- Empleados
('empleado1@cliche.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empleado', NOW(), DATE_SUB(NOW(), INTERVAL 18 MONTH), DATE_SUB(NOW(), INTERVAL 18 MONTH)),
('empleado2@cliche.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empleado', NOW(), DATE_SUB(NOW(), INTERVAL 15 MONTH), DATE_SUB(NOW(), INTERVAL 15 MONTH)),
('empleado3@cliche.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empleado', NOW(), DATE_SUB(NOW(), INTERVAL 12 MONTH), DATE_SUB(NOW(), INTERVAL 12 MONTH)),
('empleado4@cliche.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empleado', NOW(), DATE_SUB(NOW(), INTERVAL 10 MONTH), DATE_SUB(NOW(), INTERVAL 10 MONTH)),
('empleado5@cliche.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empleado', NOW(), DATE_SUB(NOW(), INTERVAL 8 MONTH), DATE_SUB(NOW(), INTERVAL 8 MONTH)),
('empleado6@cliche.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empleado', NOW(), DATE_SUB(NOW(), INTERVAL 6 MONTH), DATE_SUB(NOW(), INTERVAL 6 MONTH)),
('empleado7@cliche.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'empleado', NOW(), DATE_SUB(NOW(), INTERVAL 4 MONTH), DATE_SUB(NOW(), INTERVAL 4 MONTH)),
-- Clientes
('cliente1@empresa.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente', NOW(), DATE_SUB(NOW(), INTERVAL 14 MONTH), DATE_SUB(NOW(), INTERVAL 14 MONTH)),
('cliente2@negocio.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente', NOW(), DATE_SUB(NOW(), INTERVAL 11 MONTH), DATE_SUB(NOW(), INTERVAL 11 MONTH)),
('cliente3@startup.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente', NOW(), DATE_SUB(NOW(), INTERVAL 9 MONTH), DATE_SUB(NOW(), INTERVAL 9 MONTH)),
('cliente4@comercio.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente', NOW(), DATE_SUB(NOW(), INTERVAL 7 MONTH), DATE_SUB(NOW(), INTERVAL 7 MONTH)),
('cliente5@tienda.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente', NOW(), DATE_SUB(NOW(), INTERVAL 5 MONTH), DATE_SUB(NOW(), INTERVAL 5 MONTH)),
('cliente6@gym.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente', NOW(), DATE_SUB(NOW(), INTERVAL 3 MONTH), DATE_SUB(NOW(), INTERVAL 3 MONTH)),
('cliente7@cafe.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cliente', NOW(), DATE_SUB(NOW(), INTERVAL 2 MONTH), DATE_SUB(NOW(), INTERVAL 2 MONTH));

-- ============================================
-- EMPLEADOS (7 activos + 1 baja)
-- ============================================

INSERT INTO empleados (id_usuario, nombre, apellido_paterno, apellido_materno, telefono, puesto, fecha_ingreso, estatus) VALUES
(2, 'Juan Carlos', 'Pérez', 'García', '6141234567', 'Diseñador Gráfico Senior', DATE_SUB(NOW(), INTERVAL 18 MONTH), 'activo'),
(3, 'María Elena', 'González', 'López', '6142345678', 'Community Manager', DATE_SUB(NOW(), INTERVAL 15 MONTH), 'activo'),
(4, 'Roberto', 'Sánchez', 'Martínez', '6143456789', 'Desarrollador Web', DATE_SUB(NOW(), INTERVAL 12 MONTH), 'activo'),
(5, 'Ana Patricia', 'Ramírez', 'Torres', '6144567890', 'Coordinadora de Proyectos', DATE_SUB(NOW(), INTERVAL 10 MONTH), 'activo'),
(6, 'Luis Fernando', 'Hernández', 'Silva', '6145678901', 'Diseñador UX/UI', DATE_SUB(NOW(), INTERVAL 8 MONTH), 'activo'),
(7, 'Carmen Rosa', 'Flores', 'Mendoza', '6146789012', 'Redactora de Contenidos', DATE_SUB(NOW(), INTERVAL 6 MONTH), 'activo'),
(8, 'Miguel Ángel', 'Ruiz', 'Castro', '6147890123', 'Fotógrafo y Videógrafo', DATE_SUB(NOW(), INTERVAL 4 MONTH), 'activo');

-- Empleado dado de baja
INSERT INTO empleados (id_usuario, nombre, apellido_paterno, apellido_materno, telefono, puesto, fecha_ingreso, fecha_baja, estatus) VALUES
(1, 'Sofía', 'Mendoza', 'Reyes', '6148901234', 'Asistente Administrativa', DATE_SUB(NOW(), INTERVAL 20 MONTH), DATE_SUB(NOW(), INTERVAL 3 MONTH), 'baja');

-- ============================================
-- CLIENTES (7 activos + 2 inactivos)
-- ============================================

INSERT INTO clientes (id_usuario, nombre, apellido_paterno, apellido_materno, empresa, giro_sector, telefono, fecha_registro, estatus) VALUES
(9, 'Carlos Alberto', 'Ramírez', 'Flores', 'TechSolutions SA de CV', 'Tecnología', '6149012345', DATE_SUB(NOW(), INTERVAL 14 MONTH), 'activo'),
(10, 'Laura Patricia', 'Martínez', 'Ruiz', 'Restaurante El Buen Sabor', 'Restaurantes', '6140123456', DATE_SUB(NOW(), INTERVAL 11 MONTH), 'activo'),
(11, 'Pedro José', 'Hernández', 'Silva', 'Consultores Empresariales Pro', 'Consultoría', '6141234560', DATE_SUB(NOW(), INTERVAL 9 MONTH), 'activo'),
(12, 'Diana Carolina', 'Torres', 'Mendoza', 'Boutique Moda Actual', 'Retail', '6142345601', DATE_SUB(NOW(), INTERVAL 7 MONTH), 'activo'),
(13, 'Jorge Luis', 'López', 'Castro', 'Ferretería El Martillo', 'Comercio', '6143456012', DATE_SUB(NOW(), INTERVAL 5 MONTH), 'activo'),
(14, 'Gabriela', 'Sánchez', 'Morales', 'Gimnasio FitLife', 'Deportes y Fitness', '6144560123', DATE_SUB(NOW(), INTERVAL 3 MONTH), 'activo'),
(15, 'Ricardo', 'Flores', 'Vega', 'Cafetería Aroma', 'Alimentos y Bebidas', '6145601234', DATE_SUB(NOW(), INTERVAL 2 MONTH), 'activo');

-- Clientes inactivos
INSERT INTO clientes (id_usuario, nombre, apellido_paterno, apellido_materno, empresa, giro_sector, telefono, fecha_registro, fecha_baja, estatus) VALUES
(1, 'Mónica', 'Gutiérrez', 'Ramos', 'Inmobiliaria Casas Felices', 'Inmobiliaria', '6146012345', DATE_SUB(NOW(), INTERVAL 16 MONTH), DATE_SUB(NOW(), INTERVAL 4 MONTH), 'inactivo'),
(1, 'Andrés', 'Moreno', 'Ortiz', 'Agencia de Viajes Mundo', 'Turismo', '6147012345', DATE_SUB(NOW(), INTERVAL 13 MONTH), DATE_SUB(NOW(), INTERVAL 2 MONTH), 'inactivo');

-- ============================================
-- CATEGORÍAS DE TAREAS
-- ============================================

INSERT INTO categorias (nombre, descripcion, activo, created_at, updated_at) VALUES
('Diseño Gráfico', 'Tareas relacionadas con diseño visual, branding e identidad corporativa', 1, NOW(), NOW()),
('Desarrollo Web', 'Tareas de programación, desarrollo y mantenimiento de sitios web', 1, NOW(), NOW()),
('Redes Sociales', 'Gestión de redes sociales, community management y contenido digital', 1, NOW(), NOW()),
('Marketing Digital', 'Estrategias de marketing, campañas publicitarias y SEO', 1, NOW(), NOW()),
('Fotografía y Video', 'Producción audiovisual, fotografía de producto y edición', 1, NOW(), NOW()),
('Redacción', 'Creación de contenido escrito, copywriting y blogs', 1, NOW(), NOW());

-- ============================================
-- TAREAS (20 tareas con diferentes estados)
-- ============================================

INSERT INTO tareas (titulo, cliente_id, categoria_id, descripcion, observaciones, created_at, updated_at) VALUES
-- Cliente 1: TechSolutions
('Diseño de logo corporativo', 1, 1, 'Crear propuestas de logo para nueva marca', 'Cliente prefiere colores azul y verde', DATE_SUB(NOW(), INTERVAL 45 DAY), DATE_SUB(NOW(), INTERVAL 45 DAY)),
('Desarrollo sitio web corporativo', 1, 2, 'Sitio web responsive con 5 secciones', 'Usar React y Tailwind CSS', DATE_SUB(NOW(), INTERVAL 40 DAY), DATE_SUB(NOW(), INTERVAL 40 DAY)),
('Estrategia redes sociales Q1', 1, 3, 'Plan de contenido para primer trimestre', NULL, DATE_SUB(NOW(), INTERVAL 30 DAY), DATE_SUB(NOW(), INTERVAL 30 DAY)),

-- Cliente 2: Restaurante
('Fotografía de menú', 2, 5, 'Sesión fotográfica de 20 platillos', 'Incluir bebidas y postres', DATE_SUB(NOW(), INTERVAL 50 DAY), DATE_SUB(NOW(), INTERVAL 50 DAY)),
('Diseño de carta digital', 2, 1, 'Menú digital interactivo para tablets', NULL, DATE_SUB(NOW(), INTERVAL 35 DAY), DATE_SUB(NOW(), INTERVAL 35 DAY)),
('Gestión Instagram y Facebook', 2, 3, 'Publicaciones diarias durante 3 meses', 'Enfoque en stories y reels', DATE_SUB(NOW(), INTERVAL 25 DAY), DATE_SUB(NOW(), INTERVAL 25 DAY)),

-- Cliente 3: Consultores
('Branding completo', 3, 1, 'Identidad corporativa: logo, papelería, manual', NULL, DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_SUB(NOW(), INTERVAL 60 DAY)),
('Landing page servicios', 3, 2, 'Página de aterrizaje con formulario de contacto', 'Integrar con CRM', DATE_SUB(NOW(), INTERVAL 20 DAY), DATE_SUB(NOW(), INTERVAL 20 DAY)),
('Campaña Google Ads', 3, 4, 'Campaña publicitaria en Google por 2 meses', NULL, DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 15 DAY)),

-- Cliente 4: Boutique
('Catálogo digital productos', 4, 1, 'Diseño de catálogo con 50 productos', 'Formato PDF interactivo', DATE_SUB(NOW(), INTERVAL 55 DAY), DATE_SUB(NOW(), INTERVAL 55 DAY)),
('Video promocional', 4, 5, 'Video de 60 segundos para redes sociales', NULL, DATE_SUB(NOW(), INTERVAL 28 DAY), DATE_SUB(NOW(), INTERVAL 28 DAY)),
('Contenido blog moda', 4, 6, '8 artículos sobre tendencias de moda', 'SEO optimizado', DATE_SUB(NOW(), INTERVAL 18 DAY), DATE_SUB(NOW(), INTERVAL 18 DAY)),

-- Cliente 5: Ferretería
('Rediseño de logo', 5, 1, 'Modernizar logo existente', NULL, DATE_SUB(NOW(), INTERVAL 38 DAY), DATE_SUB(NOW(), INTERVAL 38 DAY)),
('Tienda en línea', 5, 2, 'E-commerce con catálogo de 200 productos', 'Integrar pasarela de pago', DATE_SUB(NOW(), INTERVAL 22 DAY), DATE_SUB(NOW(), INTERVAL 22 DAY)),

-- Cliente 6: Gimnasio
('Campaña Facebook Ads', 6, 4, 'Campaña de captación de nuevos socios', 'Presupuesto $5000 MXN', DATE_SUB(NOW(), INTERVAL 12 DAY), DATE_SUB(NOW(), INTERVAL 12 DAY)),
('Fotografía instalaciones', 6, 5, 'Sesión fotográfica de gimnasio y clases', NULL, DATE_SUB(NOW(), INTERVAL 8 DAY), DATE_SUB(NOW(), INTERVAL 8 DAY)),

-- Cliente 7: Cafetería
('Diseño menú impreso', 7, 1, 'Menú para imprimir en gran formato', NULL, DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY)),
('Estrategia Instagram', 7, 3, 'Plan de contenido mensual', 'Enfoque en café de especialidad', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
('Video recetas café', 7, 5, 'Serie de 5 videos cortos', NULL, DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),
('Blog sobre café', 7, 6, '4 artículos sobre cultura del café', NULL, NOW(), NOW());

-- ============================================
-- ASIGNACIONES DE TAREAS (asignar a empleados)
-- ============================================

INSERT INTO asignaciones_tareas (id_tarea, id_empleado, fecha_asignacion, fecha_completada, estatus, calificacion, comentarios, created_at, updated_at) VALUES
-- Tareas completadas
(1, 1, DATE_SUB(NOW(), INTERVAL 45 DAY), DATE_SUB(NOW(), INTERVAL 30 DAY), 'completada', 5, 'Excelente trabajo, cliente muy satisfecho', DATE_SUB(NOW(), INTERVAL 45 DAY), DATE_SUB(NOW(), INTERVAL 30 DAY)),
(4, 7, DATE_SUB(NOW(), INTERVAL 50 DAY), DATE_SUB(NOW(), INTERVAL 40 DAY), 'completada', 5, 'Fotografías de alta calidad', DATE_SUB(NOW(), INTERVAL 50 DAY), DATE_SUB(NOW(), INTERVAL 40 DAY)),
(5, 1, DATE_SUB(NOW(), INTERVAL 35 DAY), DATE_SUB(NOW(), INTERVAL 25 DAY), 'completada', 4, 'Buen diseño, pequeños ajustes solicitados', DATE_SUB(NOW(), INTERVAL 35 DAY), DATE_SUB(NOW(), INTERVAL 25 DAY)),
(7, 1, DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_SUB(NOW(), INTERVAL 45 DAY), 'completada', 5, 'Branding completo entregado a tiempo', DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_SUB(NOW(), INTERVAL 45 DAY)),
(10, 1, DATE_SUB(NOW(), INTERVAL 55 DAY), DATE_SUB(NOW(), INTERVAL 48 DAY), 'completada', 4, 'Catálogo bien diseñado', DATE_SUB(NOW(), INTERVAL 55 DAY), DATE_SUB(NOW(), INTERVAL 48 DAY)),
(13, 1, DATE_SUB(NOW(), INTERVAL 38 DAY), DATE_SUB(NOW(), INTERVAL 32 DAY), 'completada', 5, 'Logo moderno y profesional', DATE_SUB(NOW(), INTERVAL 38 DAY), DATE_SUB(NOW(), INTERVAL 32 DAY)),

-- Tareas en progreso
(2, 3, DATE_SUB(NOW(), INTERVAL 40 DAY), NULL, 'en_progreso', NULL, NULL, DATE_SUB(NOW(), INTERVAL 40 DAY), NOW()),
(8, 3, DATE_SUB(NOW(), INTERVAL 20 DAY), NULL, 'en_progreso', NULL, NULL, DATE_SUB(NOW(), INTERVAL 20 DAY), NOW()),
(14, 3, DATE_SUB(NOW(), INTERVAL 22 DAY), NULL, 'en_progreso', NULL, NULL, DATE_SUB(NOW(), INTERVAL 22 DAY), NOW()),
(11, 7, DATE_SUB(NOW(), INTERVAL 28 DAY), NULL, 'en_progreso', NULL, NULL, DATE_SUB(NOW(), INTERVAL 28 DAY), NOW()),
(16, 7, DATE_SUB(NOW(), INTERVAL 8 DAY), NULL, 'en_progreso', NULL, NULL, DATE_SUB(NOW(), INTERVAL 8 DAY), NOW()),

-- Tareas pendientes
(3, 2, DATE_SUB(NOW(), INTERVAL 30 DAY), NULL, 'pendiente', NULL, NULL, DATE_SUB(NOW(), INTERVAL 30 DAY), NOW()),
(6, 2, DATE_SUB(NOW(), INTERVAL 25 DAY), NULL, 'pendiente', NULL, NULL, DATE_SUB(NOW(), INTERVAL 25 DAY), NOW()),
(9, 4, DATE_SUB(NOW(), INTERVAL 15 DAY), NULL, 'pendiente', NULL, NULL, DATE_SUB(NOW(), INTERVAL 15 DAY), NOW()),
(12, 6, DATE_SUB(NOW(), INTERVAL 18 DAY), NULL, 'pendiente', NULL, NULL, DATE_SUB(NOW(), INTERVAL 18 DAY), NOW()),
(15, 4, DATE_SUB(NOW(), INTERVAL 12 DAY), NULL, 'pendiente', NULL, NULL, DATE_SUB(NOW(), INTERVAL 12 DAY), NOW()),
(17, 1, DATE_SUB(NOW(), INTERVAL 10 DAY), NULL, 'pendiente', NULL, NULL, DATE_SUB(NOW(), INTERVAL 10 DAY), NOW()),
(18, 2, DATE_SUB(NOW(), INTERVAL 5 DAY), NULL, 'pendiente', NULL, NULL, DATE_SUB(NOW(), INTERVAL 5 DAY), NOW()),
(19, 7, DATE_SUB(NOW(), INTERVAL 3 DAY), NULL, 'pendiente', NULL, NULL, DATE_SUB(NOW(), INTERVAL 3 DAY), NOW()),
(20, 6, NOW(), NULL, 'pendiente', NULL, NULL, NOW(), NOW());

-- ============================================
-- COTIZACIONES (15 cotizaciones variadas)
-- ============================================

INSERT INTO cotizaciones (id_cliente, titulo_cotizacion, texto_introduccion, fecha, vencimiento_dias, subtotal, iva_total, porcentaje_isr, retencion_isr, total, estatus, created_at, updated_at) VALUES
-- Aceptadas
(1, 'Paquete Diseño Web Completo', 'Propuesta para desarrollo de sitio web corporativo con diseño personalizado', DATE_SUB(NOW(), INTERVAL 60 DAY), 30, 45000.00, 7200.00, 10.00, 4500.00, 47700.00, 'aceptada', DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_SUB(NOW(), INTERVAL 50 DAY)),
(2, 'Gestión Redes Sociales - 3 meses', 'Propuesta para manejo integral de redes sociales', DATE_SUB(NOW(), INTERVAL 55 DAY), 15, 18000.00, 2880.00, 10.00, 1800.00, 19080.00, 'aceptada', DATE_SUB(NOW(), INTERVAL 55 DAY), DATE_SUB(NOW(), INTERVAL 48 DAY)),
(3, 'Branding Corporativo Completo', 'Identidad corporativa: logo, manual de marca, papelería', DATE_SUB(NOW(), INTERVAL 70 DAY), 20, 35000.00, 5600.00, 10.00, 3500.00, 37100.00, 'aceptada', DATE_SUB(NOW(), INTERVAL 70 DAY), DATE_SUB(NOW(), INTERVAL 62 DAY)),
(4, 'Catálogo Digital Interactivo', 'Diseño de catálogo digital con 50 productos', DATE_SUB(NOW(), INTERVAL 60 DAY), 15, 12000.00, 1920.00, 10.00, 1200.00, 12720.00, 'aceptada', DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_SUB(NOW(), INTERVAL 56 DAY)),
(5, 'E-commerce Completo', 'Tienda en línea con pasarela de pagos', DATE_SUB(NOW(), INTERVAL 45 DAY), 30, 65000.00, 10400.00, 10.00, 6500.00, 68900.00, 'aceptada', DATE_SUB(NOW(), INTERVAL 45 DAY), DATE_SUB(NOW(), INTERVAL 40 DAY)),
(6, 'Campaña Publicitaria Digital', 'Campaña en Facebook e Instagram por 2 meses', DATE_SUB(NOW(), INTERVAL 20 DAY), 10, 22000.00, 3520.00, 10.00, 2200.00, 23320.00, 'aceptada', DATE_SUB(NOW(), INTERVAL 20 DAY), DATE_SUB(NOW(), INTERVAL 15 DAY)),

-- Pendientes
(7, 'Estrategia de Contenidos', 'Plan de contenido para redes sociales - 6 meses', DATE_SUB(NOW(), INTERVAL 15 DAY), 15, 28000.00, 4480.00, 10.00, 2800.00, 29680.00, 'pendiente', DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 15 DAY)),
(1, 'Mantenimiento Web Anual', 'Servicio de mantenimiento y actualizaciones', DATE_SUB(NOW(), INTERVAL 10 DAY), 20, 24000.00, 3840.00, 10.00, 2400.00, 25440.00, 'pendiente', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY)),
(2, 'Video Promocional Restaurante', 'Video de 2 minutos para redes sociales', DATE_SUB(NOW(), INTERVAL 8 DAY), 10, 15000.00, 2400.00, 10.00, 1500.00, 15900.00, 'pendiente', DATE_SUB(NOW(), INTERVAL 8 DAY), DATE_SUB(NOW(), INTERVAL 8 DAY)),
(4, 'Sesión Fotográfica Productos', 'Fotografía profesional de 30 productos', DATE_SUB(NOW(), INTERVAL 5 DAY), 7, 8000.00, 1280.00, 10.00, 800.00, 8480.00, 'pendiente', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),

-- Rechazadas
(3, 'Campaña Email Marketing', 'Estrategia de email marketing por 3 meses', DATE_SUB(NOW(), INTERVAL 40 DAY), 15, 18000.00, 2880.00, 10.00, 1800.00, 19080.00, 'rechazada', DATE_SUB(NOW(), INTERVAL 40 DAY), DATE_SUB(NOW(), INTERVAL 35 DAY)),
(5, 'Rediseño de Logo', 'Actualización de identidad visual', DATE_SUB(NOW(), INTERVAL 30 DAY), 10, 8000.00, 1280.00, 10.00, 800.00, 8480.00, 'rechazada', DATE_SUB(NOW(), INTERVAL 30 DAY), DATE_SUB(NOW(), INTERVAL 28 DAY)),
(6, 'Desarrollo App Móvil', 'Aplicación móvil para iOS y Android', DATE_SUB(NOW(), INTERVAL 25 DAY), 30, 120000.00, 19200.00, 10.00, 12000.00, 127200.00, 'rechazada', DATE_SUB(NOW(), INTERVAL 25 DAY), DATE_SUB(NOW(), INTERVAL 22 DAY)),
(7, 'Paquete Básico Redes', 'Gestión básica de redes sociales - 1 mes', DATE_SUB(NOW(), INTERVAL 12 DAY), 7, 5000.00, 800.00, 10.00, 500.00, 5300.00, 'rechazada', DATE_SUB(NOW(), INTERVAL 12 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY)),
(2, 'Diseño de Menú Premium', 'Menú gourmet con fotografías profesionales', DATE_SUB(NOW(), INTERVAL 18 DAY), 10, 9500.00, 1520.00, 10.00, 950.00, 10070.00, 'rechazada', DATE_SUB(NOW(), INTERVAL 18 DAY), DATE_SUB(NOW(), INTERVAL 16 DAY));

-- ============================================
-- MINUTAS Y ACUERDOS (10 minutas)
-- ============================================

INSERT INTO minutas (id_cliente, titulo, fecha_reunion, hora_inicio, hora_fin, lugar, participantes, objetivo, temas_tratados, proximos_pasos, created_at, updated_at) VALUES
(1, 'Kick-off Proyecto Web', DATE_SUB(NOW(), INTERVAL 65 DAY), '10:00:00', '11:30:00', 'Oficinas Cliché', '["Carlos Ramírez", "Juan Pérez", "Roberto Sánchez"]', 'Definir alcance del proyecto web', 'Requerimientos funcionales, diseño, estructura, plazos de entrega', 'Enviar propuesta formal y mockups iniciales', DATE_SUB(NOW(), INTERVAL 65 DAY), DATE_SUB(NOW(), INTERVAL 65 DAY)),
(2, 'Revisión Estrategia Contenido', DATE_SUB(NOW(), INTERVAL 50 DAY), '15:00:00', '16:00:00', 'Videollamada', '["Laura Martínez", "María González"]', 'Revisar calendario de publicaciones', 'Análisis de métricas, ajustes en contenido, nuevas ideas', 'Implementar cambios sugeridos en el calendario', DATE_SUB(NOW(), INTERVAL 50 DAY), DATE_SUB(NOW(), INTERVAL 50 DAY)),
(3, 'Presentación Propuesta Branding', DATE_SUB(NOW(), INTERVAL 72 DAY), '11:00:00', '12:30:00', 'Oficinas del Cliente', '["Pedro Hernández", "Juan Pérez", "Luis Hernández"]', 'Presentar propuesta de identidad corporativa', 'Conceptos de logo, paleta de colores, tipografía, aplicaciones', 'Esperar feedback del cliente para ajustes', DATE_SUB(NOW(), INTERVAL 72 DAY), DATE_SUB(NOW(), INTERVAL 72 DAY)),
(4, 'Seguimiento Catálogo Digital', DATE_SUB(NOW(), INTERVAL 58 DAY), '14:00:00', '15:00:00', 'Oficinas Cliché', '["Diana Torres", "Juan Pérez"]', 'Revisar avances del catálogo', 'Diseño de páginas, fotografías de productos, correcciones', 'Enviar versión final para aprobación', DATE_SUB(NOW(), INTERVAL 58 DAY), DATE_SUB(NOW(), INTERVAL 58 DAY)),
(5, 'Planificación E-commerce', DATE_SUB(NOW(), INTERVAL 48 DAY), '10:00:00', '12:00:00', 'Videollamada', '["Jorge López", "Roberto Sánchez", "Ana Ramírez"]', 'Definir funcionalidades de la tienda', 'Catálogo de productos, pasarela de pago, envíos, panel admin', 'Iniciar desarrollo de la plataforma', DATE_SUB(NOW(), INTERVAL 48 DAY), DATE_SUB(NOW(), INTERVAL 48 DAY)),
(6, 'Lanzamiento Campaña Digital', DATE_SUB(NOW(), INTERVAL 22 DAY), '09:00:00', '10:00:00', 'Oficinas Cliché', '["Gabriela Sánchez", "María González", "Luis Hernández"]', 'Definir estrategia de campaña', 'Público objetivo, presupuesto, creatividades, KPIs', 'Activar campaña y monitorear resultados', DATE_SUB(NOW(), INTERVAL 22 DAY), DATE_SUB(NOW(), INTERVAL 22 DAY)),
(7, 'Revisión Estrategia Contenidos', DATE_SUB(NOW(), INTERVAL 18 DAY), '16:00:00', '17:00:00', 'Cafetería Aroma', '["Ricardo Flores", "Carmen Flores"]', 'Planificar contenido para Instagram', 'Tipos de publicaciones, frecuencia, temas, colaboraciones', 'Crear calendario editorial mensual', DATE_SUB(NOW(), INTERVAL 18 DAY), DATE_SUB(NOW(), INTERVAL 18 DAY)),
(1, 'Entrega Final Sitio Web', DATE_SUB(NOW(), INTERVAL 10 DAY), '11:00:00', '12:30:00', 'Oficinas del Cliente', '["Carlos Ramírez", "Roberto Sánchez"]', 'Presentar sitio web terminado', 'Demostración de funcionalidades, capacitación, hosting', 'Publicar sitio y dar seguimiento', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY)),
(2, 'Análisis Resultados Campaña', DATE_SUB(NOW(), INTERVAL 5 DAY), '14:00:00', '15:00:00', 'Videollamada', '["Laura Martínez", "María González"]', 'Revisar métricas de la campaña', 'Alcance, engagement, conversiones, ROI', 'Ajustar estrategia para próximo mes', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
(4, 'Planificación Campaña Primavera', NOW(), '10:00:00', '11:00:00', 'Oficinas Cliché', '["Diana Torres", "Juan Pérez", "Miguel Ruiz"]', 'Planear campaña de temporada', 'Concepto creativo, fotografías, promociones', 'Iniciar producción de contenido', NOW(), NOW());

-- Acuerdos de las minutas
INSERT INTO acuerdos (id_minuta, descripcion, responsable, fecha_compromiso, estatus, orden, created_at, updated_at) VALUES
(1, 'Enviar mockups de diseño inicial', 'Juan Pérez', DATE_SUB(NOW(), INTERVAL 58 DAY), 'completado', 1, DATE_SUB(NOW(), INTERVAL 65 DAY), DATE_SUB(NOW(), INTERVAL 58 DAY)),
(1, 'Revisar propuesta económica', 'Cliente', DATE_SUB(NOW(), INTERVAL 60 DAY), 'completado', 2, DATE_SUB(NOW(), INTERVAL 65 DAY), DATE_SUB(NOW(), INTERVAL 60 DAY)),
(2, 'Ajustar calendario de contenido', 'María González', DATE_SUB(NOW(), INTERVAL 45 DAY), 'completado', 1, DATE_SUB(NOW(), INTERVAL 50 DAY), DATE_SUB(NOW(), INTERVAL 45 DAY)),
(3, 'Realizar ajustes al logo', 'Juan Pérez', DATE_SUB(NOW(), INTERVAL 68 DAY), 'completado', 1, DATE_SUB(NOW(), INTERVAL 72 DAY), DATE_SUB(NOW(), INTERVAL 68 DAY)),
(4, 'Enviar versión final del catálogo', 'Juan Pérez', DATE_SUB(NOW(), INTERVAL 55 DAY), 'completado', 1, DATE_SUB(NOW(), INTERVAL 58 DAY), DATE_SUB(NOW(), INTERVAL 55 DAY)),
(5, 'Configurar pasarela de pagos', 'Roberto Sánchez', DATE_SUB(NOW(), INTERVAL 42 DAY), 'completado', 1, DATE_SUB(NOW(), INTERVAL 48 DAY), DATE_SUB(NOW(), INTERVAL 42 DAY)),
(6, 'Crear creatividades para campaña', 'Luis Hernández', DATE_SUB(NOW(), INTERVAL 20 DAY), 'completado', 1, DATE_SUB(NOW(), INTERVAL 22 DAY), DATE_SUB(NOW(), INTERVAL 20 DAY)),
(7, 'Elaborar calendario editorial', 'Carmen Flores', DATE_SUB(NOW(), INTERVAL 15 DAY), 'en_progreso', 1, DATE_SUB(NOW(), INTERVAL 18 DAY), NOW()),
(8, 'Capacitar al cliente en CMS', 'Roberto Sánchez', DATE_SUB(NOW(), INTERVAL 8 DAY), 'completado', 1, DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 8 DAY)),
(9, 'Preparar reporte de resultados', 'María González', DATE_ADD(NOW(), INTERVAL 2 DAY), 'pendiente', 1, DATE_SUB(NOW(), INTERVAL 5 DAY), NOW()),
(10, 'Realizar sesión fotográfica', 'Miguel Ruiz', DATE_ADD(NOW(), INTERVAL 5 DAY), 'pendiente', 1, NOW(), NOW());

-- ============================================
-- CATEGORÍAS DE SUSCRIPCIÓN
-- ============================================

INSERT INTO categorias_suscripcion (nombre, estatus) VALUES
('Software de Diseño', 'activo'),
('Hosting y Dominios', 'activo'),
('Herramientas de Marketing', 'activo'),
('Almacenamiento en la Nube', 'activo'),
('Comunicación y Colaboración', 'activo'),
('Gestión de Proyectos', 'activo');

-- ============================================
-- SUSCRIPCIONES (12 suscripciones)
-- ============================================

INSERT INTO suscripciones (idCategoria, nombre_servicio, fecha_inicio, costo, periodicidad, fecha_vencimiento, dias_recordatorio, nivel_uso, observaciones, estatus, created_at, updated_at) VALUES
-- Activas
(1, 'Adobe Creative Cloud - Plan Completo', DATE_SUB(NOW(), INTERVAL 8 MONTH), 1299.00, 'mensual', DATE_ADD(NOW(), INTERVAL 22 DAY), '[30, 15, 7]', 'alto', 'Licencia para todo el equipo de diseño', 'activo', DATE_SUB(NOW(), INTERVAL 8 MONTH), NOW()),
(1, 'Canva Pro - Equipo', DATE_SUB(NOW(), INTERVAL 6 MONTH), 599.00, 'mensual', DATE_ADD(NOW(), INTERVAL 18 DAY), '[30, 15, 7]', 'medio', 'Para diseños rápidos y redes sociales', 'activo', DATE_SUB(NOW(), INTERVAL 6 MONTH), NOW()),
(2, 'Hosting GoDaddy - Business', DATE_SUB(NOW(), INTERVAL 12 MONTH), 399.00, 'mensual', DATE_ADD(NOW(), INTERVAL 12 DAY), '[15, 7, 3]', 'alto', 'Hosting para sitios de clientes', 'activo', DATE_SUB(NOW(), INTERVAL 12 MONTH), NOW()),
(2, 'Dominio .com (x5)', DATE_SUB(NOW(), INTERVAL 10 MONTH), 250.00, 'anual', DATE_ADD(NOW(), INTERVAL 60 DAY), '[60, 30, 15]', 'medio', 'Dominios de la agencia y clientes', 'activo', DATE_SUB(NOW(), INTERVAL 10 MONTH), NOW()),
(3, 'Mailchimp - Standard', DATE_SUB(NOW(), INTERVAL 7 MONTH), 699.00, 'mensual', DATE_ADD(NOW(), INTERVAL 25 DAY), '[30, 15]', 'medio', 'Email marketing para campañas', 'activo', DATE_SUB(NOW(), INTERVAL 7 MONTH), NOW()),
(3, 'SEMrush - Pro', DATE_SUB(NOW(), INTERVAL 5 MONTH), 2499.00, 'mensual', DATE_ADD(NOW(), INTERVAL 8 DAY), '[15, 7, 3]', 'alto', 'Herramienta SEO y análisis de competencia', 'activo', DATE_SUB(NOW(), INTERVAL 5 MONTH), NOW()),
(4, 'Google Workspace - Business', DATE_SUB(NOW(), INTERVAL 14 MONTH), 1899.00, 'mensual', DATE_ADD(NOW(), INTERVAL 15 DAY), '[30, 15, 7]', 'alto', 'Correos corporativos y almacenamiento', 'activo', DATE_SUB(NOW(), INTERVAL 14 MONTH), NOW()),
(4, 'Dropbox Business', DATE_SUB(NOW(), INTERVAL 9 MONTH), 899.00, 'mensual', DATE_ADD(NOW(), INTERVAL 20 DAY), '[30, 15]', 'medio', 'Almacenamiento de archivos de proyectos', 'activo', DATE_SUB(NOW(), INTERVAL 9 MONTH), NOW()),
(5, 'Slack - Pro', DATE_SUB(NOW(), INTERVAL 11 MONTH), 850.00, 'mensual', DATE_ADD(NOW(), INTERVAL 10 DAY), '[15, 7]', 'alto', 'Comunicación interna del equipo', 'activo', DATE_SUB(NOW(), INTERVAL 11 MONTH), NOW()),
(5, 'Zoom - Pro', DATE_SUB(NOW(), INTERVAL 6 MONTH), 450.00, 'mensual', DATE_ADD(NOW(), INTERVAL 28 DAY), '[30, 15]', 'medio', 'Videollamadas con clientes', 'activo', DATE_SUB(NOW(), INTERVAL 6 MONTH), NOW()),

-- Inactivas (vencidas)
(6, 'Trello - Business', DATE_SUB(NOW(), INTERVAL 18 MONTH), 350.00, 'mensual', DATE_SUB(NOW(), INTERVAL 2 MONTH), '[30, 15, 7]', 'bajo', 'Ya no se usa, migrado a otra herramienta', 'inactivo', DATE_SUB(NOW(), INTERVAL 18 MONTH), DATE_SUB(NOW(), INTERVAL 2 MONTH)),
(1, 'Figma - Professional', DATE_SUB(NOW(), INTERVAL 15 MONTH), 799.00, 'mensual', DATE_SUB(NOW(), INTERVAL 1 MONTH), '[30, 15]', 'bajo', 'Cancelado por bajo uso', 'inactivo', DATE_SUB(NOW(), INTERVAL 15 MONTH), DATE_SUB(NOW(), INTERVAL 1 MONTH));

-- ============================================
-- EVENTOS (15 eventos variados)
-- ============================================

INSERT INTO eventos (titulo, cliente_id, creado_por, fecha, hora_inicio, hora_fin, lugar, notas, color, created_at, updated_at) VALUES
-- Eventos pasados
('Reunión de Planning Mensual', NULL, 1, DATE_SUB(NOW(), INTERVAL 30 DAY), '09:00:00', '11:00:00', 'Sala de Juntas Cliché', 'Revisión de objetivos del mes', '#3B82F6', DATE_SUB(NOW(), INTERVAL 35 DAY), DATE_SUB(NOW(), INTERVAL 35 DAY)),
('Presentación Cliente TechSolutions', 1, 3, DATE_SUB(NOW(), INTERVAL 25 DAY), '15:00:00', '16:30:00', 'Oficinas del Cliente', 'Presentar avances del sitio web', '#10B981', DATE_SUB(NOW(), INTERVAL 28 DAY), DATE_SUB(NOW(), INTERVAL 28 DAY)),
('Sesión Fotográfica Restaurante', 2, 7, DATE_SUB(NOW(), INTERVAL 20 DAY), '10:00:00', '14:00:00', 'Restaurante El Buen Sabor', 'Fotografía de platillos del menú', '#F59E0B', DATE_SUB(NOW(), INTERVAL 22 DAY), DATE_SUB(NOW(), INTERVAL 22 DAY)),
('Capacitación Adobe XD', NULL, 1, DATE_SUB(NOW(), INTERVAL 15 DAY), '16:00:00', '18:00:00', 'Online - Zoom', 'Taller interno de diseño UX', '#8B5CF6', DATE_SUB(NOW(), INTERVAL 18 DAY), DATE_SUB(NOW(), INTERVAL 18 DAY)),
('Reunión Estrategia Q2', NULL, 1, DATE_SUB(NOW(), INTERVAL 10 DAY), '10:00:00', '13:00:00', 'Sala de Juntas Cliché', 'Planificación del segundo trimestre', '#3B82F6', DATE_SUB(NOW(), INTERVAL 12 DAY), DATE_SUB(NOW(), INTERVAL 12 DAY)),
('Entrega Final E-commerce', 5, 3, DATE_SUB(NOW(), INTERVAL 5 DAY), '11:00:00', '12:30:00', 'Videollamada', 'Demostración de tienda en línea', '#10B981', DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY)),

-- Eventos próximos
('Revisión Campaña Facebook Ads', 6, 2, DATE_ADD(NOW(), INTERVAL 2 DAY), '14:00:00', '15:00:00', 'Videollamada', 'Análisis de métricas de campaña', '#EF4444', DATE_SUB(NOW(), INTERVAL 5 DAY), NOW()),
('Sesión Fotográfica Gimnasio', 6, 7, DATE_ADD(NOW(), INTERVAL 3 DAY), '09:00:00', '12:00:00', 'Gimnasio FitLife', 'Fotografía de instalaciones y clases', '#F59E0B', DATE_SUB(NOW(), INTERVAL 3 DAY), NOW()),
('Presentación Propuesta Cafetería', 7, 1, DATE_ADD(NOW(), INTERVAL 5 DAY), '10:00:00', '11:00:00', 'Cafetería Aroma', 'Presentar estrategia de redes sociales', '#10B981', NOW(), NOW()),
('Reunión Semanal de Equipo', NULL, 1, DATE_ADD(NOW(), INTERVAL 7 DAY), '09:00:00', '10:00:00', 'Sala de Juntas Cliché', 'Seguimiento de proyectos activos', '#3B82F6', NOW(), NOW()),
('Capacitación SEO Avanzado', NULL, 1, DATE_ADD(NOW(), INTERVAL 10 DAY), '15:00:00', '17:00:00', 'Online - Zoom', 'Taller de optimización SEO', '#8B5CF6', NOW(), NOW()),
('Kick-off Proyecto Boutique', 4, 1, DATE_ADD(NOW(), INTERVAL 12 DAY), '11:00:00', '12:30:00', 'Oficinas Cliché', 'Inicio de campaña de primavera', '#10B981', NOW(), NOW()),
('Revisión Mensual Resultados', NULL, 1, DATE_ADD(NOW(), INTERVAL 15 DAY), '10:00:00', '12:00:00', 'Sala de Juntas Cliché', 'Análisis de KPIs y resultados del mes', '#3B82F6', NOW(), NOW()),
('Networking Marketing Digital', NULL, 1, DATE_ADD(NOW(), INTERVAL 20 DAY), '18:00:00', '21:00:00', 'Centro de Convenciones', 'Evento de networking de la industria', '#F59E0B', NOW(), NOW()),
('Presentación Anual Clientes', NULL, 1, DATE_ADD(NOW(), INTERVAL 30 DAY), '16:00:00', '19:00:00', 'Salón de Eventos', 'Evento anual con todos los clientes', '#EF4444', NOW(), NOW());

-- ============================================
-- PLATAFORMAS Y FORMATOS
-- ============================================

INSERT INTO plataformas (nombre, icono, color) VALUES
('Facebook', '📘', '#1877F2'),
('Instagram', '📷', '#E4405F'),
('Twitter', '🐦', '#1DA1F2'),
('LinkedIn', '💼', '#0A66C2'),
('TikTok', '🎵', '#000000'),
('YouTube', '📹', '#FF0000');

INSERT INTO formatos (nombre, descripcion) VALUES
('Post', 'Publicación estándar en feed'),
('Story', 'Historia temporal de 24 horas'),
('Reel', 'Video corto vertical'),
('Carrusel', 'Múltiples imágenes deslizables'),
('Video', 'Video largo en feed'),
('Live', 'Transmisión en vivo');

-- ============================================
-- PUBLICACIONES (30 publicaciones variadas)
-- ============================================

INSERT INTO publicaciones (cliente_id, plataforma_id, formato_id, fecha, copy, arte, estatus, created_at, updated_at) VALUES
-- Cliente 1: TechSolutions - Publicadas
(1, 1, 1, DATE_SUB(NOW(), INTERVAL 15 DAY), 'Conoce nuestras soluciones tecnológicas innovadoras 🚀', 'arte_tech_01.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 20 DAY), DATE_SUB(NOW(), INTERVAL 15 DAY)),
(1, 2, 3, DATE_SUB(NOW(), INTERVAL 10 DAY), 'Tips de productividad para tu empresa 💡', 'video_tips.mp4', 'Publicado', DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY)),
(1, 4, 1, DATE_SUB(NOW(), INTERVAL 5 DAY), 'Caso de éxito: Transformación digital', 'caso_exito.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),

-- Cliente 2: Restaurante - Publicadas
(2, 2, 3, DATE_SUB(NOW(), INTERVAL 12 DAY), 'Receta del día: Tacos al pastor 🌮', 'video_tacos.mp4', 'Publicado', DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 12 DAY)),
(2, 1, 4, DATE_SUB(NOW(), INTERVAL 8 DAY), 'Nuestro menú de la semana 😋', 'carrusel_menu.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 12 DAY), DATE_SUB(NOW(), INTERVAL 8 DAY)),
(2, 2, 2, DATE_SUB(NOW(), INTERVAL 3 DAY), 'Promoción del día: 2x1 en bebidas', 'story_promo.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),

-- Cliente 3: Consultores - Publicadas
(3, 4, 1, DATE_SUB(NOW(), INTERVAL 14 DAY), 'Consejos para mejorar tu estrategia empresarial', 'post_consejos.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 18 DAY), DATE_SUB(NOW(), INTERVAL 14 DAY)),
(3, 1, 1, DATE_SUB(NOW(), INTERVAL 7 DAY), 'Webinar gratuito: Liderazgo efectivo', 'webinar.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY)),

-- Cliente 4: Boutique - Publicadas
(4, 2, 4, DATE_SUB(NOW(), INTERVAL 11 DAY), 'Nueva colección primavera 2026 🌸', 'coleccion_primavera.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 11 DAY)),
(4, 2, 3, DATE_SUB(NOW(), INTERVAL 6 DAY), 'Tendencias de moda que debes conocer', 'reel_tendencias.mp4', 'Publicado', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY)),
(4, 1, 1, DATE_SUB(NOW(), INTERVAL 2 DAY), 'Descuento especial este fin de semana', 'descuento.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),

-- Cliente 5: Ferretería - Publicadas
(5, 1, 1, DATE_SUB(NOW(), INTERVAL 9 DAY), 'Herramientas profesionales al mejor precio', 'herramientas.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 12 DAY), DATE_SUB(NOW(), INTERVAL 9 DAY)),
(5, 2, 1, DATE_SUB(NOW(), INTERVAL 4 DAY), 'Tips para el mantenimiento del hogar 🔧', 'tips_hogar.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 8 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY)),

-- Cliente 6: Gimnasio - Publicadas
(6, 2, 3, DATE_SUB(NOW(), INTERVAL 13 DAY), 'Rutina de ejercicios para principiantes', 'rutina_video.mp4', 'Publicado', DATE_SUB(NOW(), INTERVAL 16 DAY), DATE_SUB(NOW(), INTERVAL 13 DAY)),
(6, 1, 1, DATE_SUB(NOW(), INTERVAL 8 DAY), 'Promoción: 3 meses por el precio de 2', 'promo_gym.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 11 DAY), DATE_SUB(NOW(), INTERVAL 8 DAY)),

-- Cliente 7: Cafetería - Publicadas
(7, 2, 3, DATE_SUB(NOW(), INTERVAL 10 DAY), 'Cómo preparar el café perfecto ☕', 'video_cafe.mp4', 'Publicado', DATE_SUB(NOW(), INTERVAL 13 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY)),
(7, 2, 2, DATE_SUB(NOW(), INTERVAL 5 DAY), 'Café del día: Espresso con notas de chocolate', 'story_espresso.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 8 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),

-- Publicaciones Pendientes (próximas)
(1, 2, 1, DATE_ADD(NOW(), INTERVAL 2 DAY), 'Lanzamiento de nuevo servicio cloud', 'nuevo_servicio.jpg', 'Pendiente', NOW(), NOW()),
(2, 2, 3, DATE_ADD(NOW(), INTERVAL 3 DAY), 'Receta especial de fin de semana', 'receta_especial.mp4', 'Pendiente', NOW(), NOW()),
(3, 4, 1, DATE_ADD(NOW(), INTERVAL 4 DAY), 'Artículo: Transformación digital en PyMEs', 'articulo_digital.jpg', 'Pendiente', NOW(), NOW()),
(4, 2, 4, DATE_ADD(NOW(), INTERVAL 5 DAY), 'Lookbook primavera-verano', 'lookbook.jpg', 'Pendiente', NOW(), NOW()),
(5, 1, 1, DATE_ADD(NOW(), INTERVAL 6 DAY), 'Ofertas de la semana en herramientas', 'ofertas_semana.jpg', 'Pendiente', NOW(), NOW()),
(6, 2, 3, DATE_ADD(NOW(), INTERVAL 7 DAY), 'Ejercicios para fortalecer core', 'ejercicios_core.mp4', 'Pendiente', NOW(), NOW()),
(7, 2, 1, DATE_ADD(NOW(), INTERVAL 8 DAY), 'Nuevo menú de postres', 'menu_postres.jpg', 'Pendiente', NOW(), NOW()),

-- Publicaciones para Reprogramar
(1, 1, 1, DATE_SUB(NOW(), INTERVAL 1 DAY), 'Post sobre innovación tecnológica', 'innovacion.jpg', 'Reprogramar', DATE_SUB(NOW(), INTERVAL 5 DAY), NOW()),
(2, 2, 2, NOW(), 'Story sobre platillo especial', 'platillo_especial.jpg', 'Reprogramar', DATE_SUB(NOW(), INTERVAL 3 DAY), NOW()),
(4, 2, 3, DATE_ADD(NOW(), INTERVAL 1 DAY), 'Reel de tendencias de moda', 'tendencias_reel.mp4', 'Reprogramar', DATE_SUB(NOW(), INTERVAL 2 DAY), NOW()),
(6, 1, 1, DATE_ADD(NOW(), INTERVAL 2 DAY), 'Testimonial de cliente satisfecho', 'testimonial.jpg', 'Reprogramar', DATE_SUB(NOW(), INTERVAL 1 DAY), NOW()),
(7, 2, 1, DATE_ADD(NOW(), INTERVAL 3 DAY), 'Café de especialidad del mes', 'cafe_mes.jpg', 'Reprogramar', NOW(), NOW());

-- Mensaje final
SELECT '✅ Base de datos poblada exitosamente!' AS mensaje;
SELECT 'Total de registros insertados:' AS resumen;
SELECT COUNT(*) AS usuarios FROM users;
SELECT COUNT(*) AS empleados FROM empleados;
SELECT COUNT(*) AS clientes FROM clientes;
SELECT COUNT(*) AS categorias_tareas FROM categorias;
SELECT COUNT(*) AS tareas FROM tareas;
SELECT COUNT(*) AS asignaciones FROM asignaciones_tareas;
SELECT COUNT(*) AS cotizaciones FROM cotizaciones;
SELECT COUNT(*) AS minutas FROM minutas;
SELECT COUNT(*) AS acuerdos FROM acuerdos;
SELECT COUNT(*) AS categorias_suscripcion FROM categorias_suscripcion;
SELECT COUNT(*) AS suscripciones FROM suscripciones;
SELECT COUNT(*) AS eventos FROM eventos;
SELECT COUNT(*) AS plataformas FROM plataformas;
SELECT COUNT(*) AS formatos FROM formatos;
SELECT COUNT(*) AS publicaciones FROM publicaciones;
