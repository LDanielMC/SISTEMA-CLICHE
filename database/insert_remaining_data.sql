-- Categorías de tareas
INSERT INTO categorias (nombre, descripcion, activo, created_at, updated_at) VALUES
('Diseño Gráfico', 'Tareas de diseño visual y branding', 1, NOW(), NOW()),
('Desarrollo Web', 'Programación y desarrollo web', 1, NOW(), NOW()),
('Redes Sociales', 'Gestión de redes sociales', 1, NOW(), NOW()),
('Marketing Digital', 'Estrategias de marketing', 1, NOW(), NOW()),
('Fotografía y Video', 'Producción audiovisual', 1, NOW(), NOW()),
('Redacción', 'Creación de contenido escrito', 1, NOW(), NOW());

-- Tareas (20 tareas)
INSERT INTO tareas (titulo, cliente_id, categoria_id, descripcion, observaciones, created_at, updated_at) VALUES
('Diseño de logo corporativo', 1, 1, 'Crear propuestas de logo para nueva marca', 'Cliente prefiere colores azul y verde', DATE_SUB(NOW(), INTERVAL 45 DAY), DATE_SUB(NOW(), INTERVAL 45 DAY)),
('Desarrollo sitio web', 1, 2, 'Sitio web responsive con 5 secciones', 'Usar React y Tailwind', DATE_SUB(NOW(), INTERVAL 40 DAY), DATE_SUB(NOW(), INTERVAL 40 DAY)),
('Estrategia redes sociales', 1, 3, 'Plan de contenido trimestral', NULL, DATE_SUB(NOW(), INTERVAL 30 DAY), DATE_SUB(NOW(), INTERVAL 30 DAY)),
('Fotografía de menú', 2, 5, 'Sesión fotográfica de 20 platillos', 'Incluir bebidas', DATE_SUB(NOW(), INTERVAL 50 DAY), DATE_SUB(NOW(), INTERVAL 50 DAY)),
('Diseño de carta digital', 2, 1, 'Menú digital para tablets', NULL, DATE_SUB(NOW(), INTERVAL 35 DAY), DATE_SUB(NOW(), INTERVAL 35 DAY)),
('Gestión Instagram', 2, 3, 'Publicaciones diarias 3 meses', 'Enfoque en stories', DATE_SUB(NOW(), INTERVAL 25 DAY), DATE_SUB(NOW(), INTERVAL 25 DAY)),
('Branding completo', 3, 1, 'Identidad corporativa completa', NULL, DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_SUB(NOW(), INTERVAL 60 DAY)),
('Landing page', 3, 2, 'Página con formulario de contacto', 'Integrar CRM', DATE_SUB(NOW(), INTERVAL 20 DAY), DATE_SUB(NOW(), INTERVAL 20 DAY)),
('Campaña Google Ads', 3, 4, 'Campaña publicitaria 2 meses', NULL, DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 15 DAY)),
('Catálogo digital', 4, 1, 'Catálogo con 50 productos', 'PDF interactivo', DATE_SUB(NOW(), INTERVAL 55 DAY), DATE_SUB(NOW(), INTERVAL 55 DAY)),
('Video promocional', 4, 5, 'Video de 60 segundos', NULL, DATE_SUB(NOW(), INTERVAL 28 DAY), DATE_SUB(NOW(), INTERVAL 28 DAY)),
('Contenido blog moda', 4, 6, '8 artículos sobre tendencias', 'SEO optimizado', DATE_SUB(NOW(), INTERVAL 18 DAY), DATE_SUB(NOW(), INTERVAL 18 DAY)),
('Rediseño de logo', 5, 1, 'Modernizar logo existente', NULL, DATE_SUB(NOW(), INTERVAL 38 DAY), DATE_SUB(NOW(), INTERVAL 38 DAY)),
('Tienda en línea', 5, 2, 'E-commerce con 200 productos', 'Pasarela de pago', DATE_SUB(NOW(), INTERVAL 22 DAY), DATE_SUB(NOW(), INTERVAL 22 DAY)),
('Campaña Facebook Ads', 6, 4, 'Captación de nuevos socios', 'Presupuesto $5000', DATE_SUB(NOW(), INTERVAL 12 DAY), DATE_SUB(NOW(), INTERVAL 12 DAY)),
('Fotografía instalaciones', 6, 5, 'Sesión de gimnasio y clases', NULL, DATE_SUB(NOW(), INTERVAL 8 DAY), DATE_SUB(NOW(), INTERVAL 8 DAY)),
('Diseño menú impreso', 7, 1, 'Menú para gran formato', NULL, DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY)),
('Estrategia Instagram', 7, 3, 'Plan de contenido mensual', 'Café de especialidad', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
('Video recetas café', 7, 5, 'Serie de 5 videos cortos', NULL, DATE_SUB(NOW(), INTERVAL 3 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),
('Blog sobre café', 7, 6, '4 artículos sobre cultura', NULL, NOW(), NOW());

-- Asignaciones (ajustadas a la estructura real)
INSERT INTO asignaciones_tareas (tarea_id, empleado_id, prioridad, fecha_limite, estado_empleado, estado_admin, created_at, updated_at) VALUES
(1, 1, 'alta', DATE_SUB(NOW(), INTERVAL 30 DAY), 'terminada', 'completa', DATE_SUB(NOW(), INTERVAL 45 DAY), DATE_SUB(NOW(), INTERVAL 30 DAY)),
(2, 3, 'alta', DATE_ADD(NOW(), INTERVAL 10 DAY), 'en_proceso', 'pendiente', DATE_SUB(NOW(), INTERVAL 40 DAY), NOW()),
(3, 2, 'media', DATE_ADD(NOW(), INTERVAL 15 DAY), 'asignada', 'pendiente', DATE_SUB(NOW(), INTERVAL 30 DAY), NOW()),
(4, 7, 'alta', DATE_SUB(NOW(), INTERVAL 40 DAY), 'terminada', 'completa', DATE_SUB(NOW(), INTERVAL 50 DAY), DATE_SUB(NOW(), INTERVAL 40 DAY)),
(5, 1, 'media', DATE_SUB(NOW(), INTERVAL 25 DAY), 'terminada', 'completa', DATE_SUB(NOW(), INTERVAL 35 DAY), DATE_SUB(NOW(), INTERVAL 25 DAY)),
(6, 2, 'media', DATE_ADD(NOW(), INTERVAL 5 DAY), 'asignada', 'pendiente', DATE_SUB(NOW(), INTERVAL 25 DAY), NOW()),
(7, 1, 'alta', DATE_SUB(NOW(), INTERVAL 45 DAY), 'terminada', 'completa', DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_SUB(NOW(), INTERVAL 45 DAY)),
(8, 3, 'alta', DATE_ADD(NOW(), INTERVAL 8 DAY), 'en_proceso', 'pendiente', DATE_SUB(NOW(), INTERVAL 20 DAY), NOW()),
(9, 4, 'media', DATE_ADD(NOW(), INTERVAL 12 DAY), 'asignada', 'pendiente', DATE_SUB(NOW(), INTERVAL 15 DAY), NOW()),
(10, 1, 'media', DATE_SUB(NOW(), INTERVAL 48 DAY), 'terminada', 'completa', DATE_SUB(NOW(), INTERVAL 55 DAY), DATE_SUB(NOW(), INTERVAL 48 DAY)),
(11, 7, 'media', DATE_ADD(NOW(), INTERVAL 7 DAY), 'en_proceso', 'pendiente', DATE_SUB(NOW(), INTERVAL 28 DAY), NOW()),
(12, 6, 'baja', DATE_ADD(NOW(), INTERVAL 20 DAY), 'asignada', 'pendiente', DATE_SUB(NOW(), INTERVAL 18 DAY), NOW()),
(13, 1, 'media', DATE_SUB(NOW(), INTERVAL 32 DAY), 'terminada', 'completa', DATE_SUB(NOW(), INTERVAL 38 DAY), DATE_SUB(NOW(), INTERVAL 32 DAY)),
(14, 3, 'alta', DATE_ADD(NOW(), INTERVAL 15 DAY), 'en_proceso', 'pendiente', DATE_SUB(NOW(), INTERVAL 22 DAY), NOW()),
(15, 4, 'alta', DATE_ADD(NOW(), INTERVAL 5 DAY), 'asignada', 'pendiente', DATE_SUB(NOW(), INTERVAL 12 DAY), NOW()),
(16, 7, 'media', DATE_ADD(NOW(), INTERVAL 3 DAY), 'en_proceso', 'pendiente', DATE_SUB(NOW(), INTERVAL 8 DAY), NOW()),
(17, 1, 'baja', DATE_ADD(NOW(), INTERVAL 10 DAY), 'asignada', 'pendiente', DATE_SUB(NOW(), INTERVAL 10 DAY), NOW()),
(18, 2, 'media', DATE_ADD(NOW(), INTERVAL 8 DAY), 'asignada', 'pendiente', DATE_SUB(NOW(), INTERVAL 5 DAY), NOW()),
(19, 7, 'baja', DATE_ADD(NOW(), INTERVAL 12 DAY), 'asignada', 'pendiente', DATE_SUB(NOW(), INTERVAL 3 DAY), NOW()),
(20, 6, 'media', DATE_ADD(NOW(), INTERVAL 15 DAY), 'asignada', 'pendiente', NOW(), NOW());

-- Cotizaciones (15 cotizaciones)
INSERT INTO cotizaciones (id_cliente, titulo_cotizacion, texto_introduccion, fecha, vencimiento_dias, subtotal, iva_total, porcentaje_isr, retencion_isr, total, estatus, created_at, updated_at) VALUES
(1, 'Paquete Diseño Web Completo', 'Desarrollo de sitio web corporativo', DATE_SUB(NOW(), INTERVAL 60 DAY), 30, 45000.00, 7200.00, 10.00, 4500.00, 47700.00, 'aceptada', DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_SUB(NOW(), INTERVAL 50 DAY)),
(2, 'Gestión Redes Sociales - 3 meses', 'Manejo integral de redes', DATE_SUB(NOW(), INTERVAL 55 DAY), 15, 18000.00, 2880.00, 10.00, 1800.00, 19080.00, 'aceptada', DATE_SUB(NOW(), INTERVAL 55 DAY), DATE_SUB(NOW(), INTERVAL 48 DAY)),
(3, 'Branding Corporativo', 'Identidad corporativa completa', DATE_SUB(NOW(), INTERVAL 70 DAY), 20, 35000.00, 5600.00, 10.00, 3500.00, 37100.00, 'aceptada', DATE_SUB(NOW(), INTERVAL 70 DAY), DATE_SUB(NOW(), INTERVAL 62 DAY)),
(4, 'Catálogo Digital', 'Catálogo con 50 productos', DATE_SUB(NOW(), INTERVAL 60 DAY), 15, 12000.00, 1920.00, 10.00, 1200.00, 12720.00, 'aceptada', DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_SUB(NOW(), INTERVAL 56 DAY)),
(5, 'E-commerce Completo', 'Tienda con pasarela de pagos', DATE_SUB(NOW(), INTERVAL 45 DAY), 30, 65000.00, 10400.00, 10.00, 6500.00, 68900.00, 'aceptada', DATE_SUB(NOW(), INTERVAL 45 DAY), DATE_SUB(NOW(), INTERVAL 40 DAY)),
(6, 'Campaña Publicitaria', 'Campaña en Facebook e Instagram', DATE_SUB(NOW(), INTERVAL 20 DAY), 10, 22000.00, 3520.00, 10.00, 2200.00, 23320.00, 'aceptada', DATE_SUB(NOW(), INTERVAL 20 DAY), DATE_SUB(NOW(), INTERVAL 15 DAY)),
(7, 'Estrategia de Contenidos', 'Plan de contenido 6 meses', DATE_SUB(NOW(), INTERVAL 15 DAY), 15, 28000.00, 4480.00, 10.00, 2800.00, 29680.00, 'pendiente', DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 15 DAY)),
(1, 'Mantenimiento Web Anual', 'Servicio de mantenimiento', DATE_SUB(NOW(), INTERVAL 10 DAY), 20, 24000.00, 3840.00, 10.00, 2400.00, 25440.00, 'pendiente', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY)),
(2, 'Video Promocional', 'Video de 2 minutos', DATE_SUB(NOW(), INTERVAL 8 DAY), 10, 15000.00, 2400.00, 10.00, 1500.00, 15900.00, 'pendiente', DATE_SUB(NOW(), INTERVAL 8 DAY), DATE_SUB(NOW(), INTERVAL 8 DAY)),
(4, 'Sesión Fotográfica', 'Fotografía de 30 productos', DATE_SUB(NOW(), INTERVAL 5 DAY), 7, 8000.00, 1280.00, 10.00, 800.00, 8480.00, 'pendiente', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
(3, 'Campaña Email Marketing', 'Email marketing 3 meses', DATE_SUB(NOW(), INTERVAL 40 DAY), 15, 18000.00, 2880.00, 10.00, 1800.00, 19080.00, 'rechazada', DATE_SUB(NOW(), INTERVAL 40 DAY), DATE_SUB(NOW(), INTERVAL 35 DAY)),
(5, 'Rediseño de Logo', 'Actualización de identidad', DATE_SUB(NOW(), INTERVAL 30 DAY), 10, 8000.00, 1280.00, 10.00, 800.00, 8480.00, 'rechazada', DATE_SUB(NOW(), INTERVAL 30 DAY), DATE_SUB(NOW(), INTERVAL 28 DAY)),
(6, 'Desarrollo App Móvil', 'App para iOS y Android', DATE_SUB(NOW(), INTERVAL 25 DAY), 30, 120000.00, 19200.00, 10.00, 12000.00, 127200.00, 'rechazada', DATE_SUB(NOW(), INTERVAL 25 DAY), DATE_SUB(NOW(), INTERVAL 22 DAY)),
(7, 'Paquete Básico Redes', 'Gestión básica 1 mes', DATE_SUB(NOW(), INTERVAL 12 DAY), 7, 5000.00, 800.00, 10.00, 500.00, 5300.00, 'rechazada', DATE_SUB(NOW(), INTERVAL 12 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY)),
(2, 'Diseño Menú Premium', 'Menú con fotografías', DATE_SUB(NOW(), INTERVAL 18 DAY), 10, 9500.00, 1520.00, 10.00, 950.00, 10070.00, 'rechazada', DATE_SUB(NOW(), INTERVAL 18 DAY), DATE_SUB(NOW(), INTERVAL 16 DAY));

-- Minutas (ajustadas a estructura real)
INSERT INTO minutas (id_cliente, titulo, fecha, asistentes, puntos_tratados, observaciones, created_at, updated_at) VALUES
(1, 'Kick-off Proyecto Web', DATE_SUB(NOW(), INTERVAL 65 DAY), 'Carlos Ramírez, Juan Pérez, Roberto Sánchez', 'Requerimientos funcionales, diseño, estructura, plazos', 'Enviar propuesta formal y mockups', DATE_SUB(NOW(), INTERVAL 65 DAY), DATE_SUB(NOW(), INTERVAL 65 DAY)),
(2, 'Revisión Estrategia Contenido', DATE_SUB(NOW(), INTERVAL 50 DAY), 'Laura Martínez, María González', 'Análisis de métricas, ajustes en contenido', 'Implementar cambios sugeridos', DATE_SUB(NOW(), INTERVAL 50 DAY), DATE_SUB(NOW(), INTERVAL 50 DAY)),
(3, 'Presentación Branding', DATE_SUB(NOW(), INTERVAL 72 DAY), 'Pedro Hernández, Juan Pérez, Luis Hernández', 'Conceptos de logo, paleta, tipografía', 'Esperar feedback del cliente', DATE_SUB(NOW(), INTERVAL 72 DAY), DATE_SUB(NOW(), INTERVAL 72 DAY)),
(4, 'Seguimiento Catálogo', DATE_SUB(NOW(), INTERVAL 58 DAY), 'Diana Torres, Juan Pérez', 'Diseño de páginas, fotografías, correcciones', 'Enviar versión final', DATE_SUB(NOW(), INTERVAL 58 DAY), DATE_SUB(NOW(), INTERVAL 58 DAY)),
(5, 'Planificación E-commerce', DATE_SUB(NOW(), INTERVAL 48 DAY), 'Jorge López, Roberto Sánchez, Ana Ramírez', 'Catálogo, pasarela de pago, envíos', 'Iniciar desarrollo', DATE_SUB(NOW(), INTERVAL 48 DAY), DATE_SUB(NOW(), INTERVAL 48 DAY)),
(6, 'Lanzamiento Campaña', DATE_SUB(NOW(), INTERVAL 22 DAY), 'Gabriela Sánchez, María González', 'Público objetivo, presupuesto, creatividades', 'Activar campaña', DATE_SUB(NOW(), INTERVAL 22 DAY), DATE_SUB(NOW(), INTERVAL 22 DAY)),
(7, 'Revisión Contenidos', DATE_SUB(NOW(), INTERVAL 18 DAY), 'Ricardo Flores, Carmen Flores', 'Tipos de publicaciones, frecuencia', 'Crear calendario editorial', DATE_SUB(NOW(), INTERVAL 18 DAY), DATE_SUB(NOW(), INTERVAL 18 DAY)),
(1, 'Entrega Final Web', DATE_SUB(NOW(), INTERVAL 10 DAY), 'Carlos Ramírez, Roberto Sánchez', 'Demostración, capacitación, hosting', 'Publicar sitio', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY)),
(2, 'Análisis Resultados', DATE_SUB(NOW(), INTERVAL 5 DAY), 'Laura Martínez, María González', 'Alcance, engagement, conversiones, ROI', 'Ajustar estrategia', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
(4, 'Planificación Campaña', NOW(), 'Diana Torres, Juan Pérez, Miguel Ruiz', 'Concepto creativo, fotografías', 'Iniciar producción', NOW(), NOW());

-- Acuerdos (ajustados a estructura real)
INSERT INTO acuerdos (id_minuta, acuerdo, responsable, orden, estatus, fecha_limite, created_at, updated_at) VALUES
(1, 'Enviar mockups de diseño inicial', 'Juan Pérez', 1, 'completado', DATE_SUB(NOW(), INTERVAL 58 DAY), DATE_SUB(NOW(), INTERVAL 65 DAY), DATE_SUB(NOW(), INTERVAL 58 DAY)),
(1, 'Revisar propuesta económica', 'Cliente', 2, 'completado', DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_SUB(NOW(), INTERVAL 65 DAY), DATE_SUB(NOW(), INTERVAL 60 DAY)),
(2, 'Ajustar calendario de contenido', 'María González', 1, 'completado', DATE_SUB(NOW(), INTERVAL 45 DAY), DATE_SUB(NOW(), INTERVAL 50 DAY), DATE_SUB(NOW(), INTERVAL 45 DAY)),
(3, 'Realizar ajustes al logo', 'Juan Pérez', 1, 'completado', DATE_SUB(NOW(), INTERVAL 68 DAY), DATE_SUB(NOW(), INTERVAL 72 DAY), DATE_SUB(NOW(), INTERVAL 68 DAY)),
(4, 'Enviar versión final', 'Juan Pérez', 1, 'completado', DATE_SUB(NOW(), INTERVAL 55 DAY), DATE_SUB(NOW(), INTERVAL 58 DAY), DATE_SUB(NOW(), INTERVAL 55 DAY)),
(5, 'Configurar pasarela de pagos', 'Roberto Sánchez', 1, 'completado', DATE_SUB(NOW(), INTERVAL 42 DAY), DATE_SUB(NOW(), INTERVAL 48 DAY), DATE_SUB(NOW(), INTERVAL 42 DAY)),
(6, 'Crear creatividades', 'Luis Hernández', 1, 'completado', DATE_SUB(NOW(), INTERVAL 20 DAY), DATE_SUB(NOW(), INTERVAL 22 DAY), DATE_SUB(NOW(), INTERVAL 20 DAY)),
(7, 'Elaborar calendario editorial', 'Carmen Flores', 1, 'pendiente', DATE_ADD(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 18 DAY), NOW()),
(8, 'Capacitar al cliente', 'Roberto Sánchez', 1, 'completado', DATE_SUB(NOW(), INTERVAL 8 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 8 DAY)),
(9, 'Preparar reporte', 'María González', 1, 'pendiente', DATE_ADD(NOW(), INTERVAL 2 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY), NOW()),
(10, 'Realizar sesión fotográfica', 'Miguel Ruiz', 1, 'pendiente', DATE_ADD(NOW(), INTERVAL 5 DAY), NOW(), NOW());

-- Categorías de suscripción
INSERT INTO categorias_suscripcion (nombre, estatus) VALUES
('Software de Diseño', 'activo'),
('Hosting y Dominios', 'activo'),
('Herramientas de Marketing', 'activo'),
('Almacenamiento en la Nube', 'activo'),
('Comunicación', 'activo'),
('Gestión de Proyectos', 'activo');

-- Suscripciones (12 suscripciones)
INSERT INTO suscripciones (idCategoria, nombre_servicio, fecha_inicio, costo, periodicidad, fecha_vencimiento, dias_recordatorio, nivel_uso, observaciones, estatus, created_at, updated_at) VALUES
(1, 'Adobe Creative Cloud', DATE_SUB(NOW(), INTERVAL 8 MONTH), 1299.00, 'mensual', DATE_ADD(NOW(), INTERVAL 22 DAY), '[30, 15, 7]', 'alto', 'Licencia para equipo de diseño', 'activo', DATE_SUB(NOW(), INTERVAL 8 MONTH), NOW()),
(1, 'Canva Pro', DATE_SUB(NOW(), INTERVAL 6 MONTH), 599.00, 'mensual', DATE_ADD(NOW(), INTERVAL 18 DAY), '[30, 15, 7]', 'medio', 'Diseños rápidos', 'activo', DATE_SUB(NOW(), INTERVAL 6 MONTH), NOW()),
(2, 'Hosting GoDaddy', DATE_SUB(NOW(), INTERVAL 12 MONTH), 399.00, 'mensual', DATE_ADD(NOW(), INTERVAL 12 DAY), '[15, 7, 3]', 'alto', 'Hosting para clientes', 'activo', DATE_SUB(NOW(), INTERVAL 12 MONTH), NOW()),
(2, 'Dominios .com (x5)', DATE_SUB(NOW(), INTERVAL 10 MONTH), 250.00, 'anual', DATE_ADD(NOW(), INTERVAL 60 DAY), '[60, 30, 15]', 'medio', 'Dominios de la agencia', 'activo', DATE_SUB(NOW(), INTERVAL 10 MONTH), NOW()),
(3, 'Mailchimp Standard', DATE_SUB(NOW(), INTERVAL 7 MONTH), 699.00, 'mensual', DATE_ADD(NOW(), INTERVAL 25 DAY), '[30, 15]', 'medio', 'Email marketing', 'activo', DATE_SUB(NOW(), INTERVAL 7 MONTH), NOW()),
(3, 'SEMrush Pro', DATE_SUB(NOW(), INTERVAL 5 MONTH), 2499.00, 'mensual', DATE_ADD(NOW(), INTERVAL 8 DAY), '[15, 7, 3]', 'alto', 'Herramienta SEO', 'activo', DATE_SUB(NOW(), INTERVAL 5 MONTH), NOW()),
(4, 'Google Workspace', DATE_SUB(NOW(), INTERVAL 14 MONTH), 1899.00, 'mensual', DATE_ADD(NOW(), INTERVAL 15 DAY), '[30, 15, 7]', 'alto', 'Correos corporativos', 'activo', DATE_SUB(NOW(), INTERVAL 14 MONTH), NOW()),
(4, 'Dropbox Business', DATE_SUB(NOW(), INTERVAL 9 MONTH), 899.00, 'mensual', DATE_ADD(NOW(), INTERVAL 20 DAY), '[30, 15]', 'medio', 'Almacenamiento', 'activo', DATE_SUB(NOW(), INTERVAL 9 MONTH), NOW()),
(5, 'Slack Pro', DATE_SUB(NOW(), INTERVAL 11 MONTH), 850.00, 'mensual', DATE_ADD(NOW(), INTERVAL 10 DAY), '[15, 7]', 'alto', 'Comunicación interna', 'activo', DATE_SUB(NOW(), INTERVAL 11 MONTH), NOW()),
(5, 'Zoom Pro', DATE_SUB(NOW(), INTERVAL 6 MONTH), 450.00, 'mensual', DATE_ADD(NOW(), INTERVAL 28 DAY), '[30, 15]', 'medio', 'Videollamadas', 'activo', DATE_SUB(NOW(), INTERVAL 6 MONTH), NOW()),
(6, 'Trello Business', DATE_SUB(NOW(), INTERVAL 18 MONTH), 350.00, 'mensual', DATE_SUB(NOW(), INTERVAL 2 MONTH), '[30, 15, 7]', 'bajo', 'Ya no se usa', 'inactivo', DATE_SUB(NOW(), INTERVAL 18 MONTH), DATE_SUB(NOW(), INTERVAL 2 MONTH)),
(1, 'Figma Professional', DATE_SUB(NOW(), INTERVAL 15 MONTH), 799.00, 'mensual', DATE_SUB(NOW(), INTERVAL 1 MONTH), '[30, 15]', 'bajo', 'Cancelado', 'inactivo', DATE_SUB(NOW(), INTERVAL 15 MONTH), DATE_SUB(NOW(), INTERVAL 1 MONTH));

-- Eventos (15 eventos)
INSERT INTO eventos (titulo, cliente_id, creado_por, fecha, hora_inicio, hora_fin, lugar, notas, color, created_at, updated_at) VALUES
('Planning Mensual', NULL, 1, DATE_SUB(NOW(), INTERVAL 30 DAY), '09:00:00', '11:00:00', 'Sala de Juntas', 'Revisión de objetivos', '#3B82F6', DATE_SUB(NOW(), INTERVAL 35 DAY), DATE_SUB(NOW(), INTERVAL 35 DAY)),
('Presentación TechSolutions', 1, 3, DATE_SUB(NOW(), INTERVAL 25 DAY), '15:00:00', '16:30:00', 'Oficinas Cliente', 'Avances del sitio', '#10B981', DATE_SUB(NOW(), INTERVAL 28 DAY), DATE_SUB(NOW(), INTERVAL 28 DAY)),
('Sesión Fotográfica', 2, 7, DATE_SUB(NOW(), INTERVAL 20 DAY), '10:00:00', '14:00:00', 'Restaurante', 'Fotografía de platillos', '#F59E0B', DATE_SUB(NOW(), INTERVAL 22 DAY), DATE_SUB(NOW(), INTERVAL 22 DAY)),
('Capacitación Adobe XD', NULL, 1, DATE_SUB(NOW(), INTERVAL 15 DAY), '16:00:00', '18:00:00', 'Online - Zoom', 'Taller interno', '#8B5CF6', DATE_SUB(NOW(), INTERVAL 18 DAY), DATE_SUB(NOW(), INTERVAL 18 DAY)),
('Reunión Estrategia Q2', NULL, 1, DATE_SUB(NOW(), INTERVAL 10 DAY), '10:00:00', '13:00:00', 'Sala de Juntas', 'Planificación trimestral', '#3B82F6', DATE_SUB(NOW(), INTERVAL 12 DAY), DATE_SUB(NOW(), INTERVAL 12 DAY)),
('Entrega E-commerce', 5, 3, DATE_SUB(NOW(), INTERVAL 5 DAY), '11:00:00', '12:30:00', 'Videollamada', 'Demostración tienda', '#10B981', DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY)),
('Revisión Campaña Ads', 6, 2, DATE_ADD(NOW(), INTERVAL 2 DAY), '14:00:00', '15:00:00', 'Videollamada', 'Análisis de métricas', '#EF4444', DATE_SUB(NOW(), INTERVAL 5 DAY), NOW()),
('Sesión Gimnasio', 6, 7, DATE_ADD(NOW(), INTERVAL 3 DAY), '09:00:00', '12:00:00', 'Gimnasio FitLife', 'Fotografía instalaciones', '#F59E0B', DATE_SUB(NOW(), INTERVAL 3 DAY), NOW()),
('Propuesta Cafetería', 7, 1, DATE_ADD(NOW(), INTERVAL 5 DAY), '10:00:00', '11:00:00', 'Cafetería Aroma', 'Estrategia redes', '#10B981', NOW(), NOW()),
('Reunión Semanal', NULL, 1, DATE_ADD(NOW(), INTERVAL 7 DAY), '09:00:00', '10:00:00', 'Sala de Juntas', 'Seguimiento proyectos', '#3B82F6', NOW(), NOW()),
('Capacitación SEO', NULL, 1, DATE_ADD(NOW(), INTERVAL 10 DAY), '15:00:00', '17:00:00', 'Online - Zoom', 'Taller SEO', '#8B5CF6', NOW(), NOW()),
('Kick-off Boutique', 4, 1, DATE_ADD(NOW(), INTERVAL 12 DAY), '11:00:00', '12:30:00', 'Oficinas Cliché', 'Campaña primavera', '#10B981', NOW(), NOW()),
('Revisión Mensual', NULL, 1, DATE_ADD(NOW(), INTERVAL 15 DAY), '10:00:00', '12:00:00', 'Sala de Juntas', 'Análisis de KPIs', '#3B82F6', NOW(), NOW()),
('Networking', NULL, 1, DATE_ADD(NOW(), INTERVAL 20 DAY), '18:00:00', '21:00:00', 'Centro Convenciones', 'Evento networking', '#F59E0B', NOW(), NOW()),
('Presentación Anual', NULL, 1, DATE_ADD(NOW(), INTERVAL 30 DAY), '16:00:00', '19:00:00', 'Salón Eventos', 'Evento con clientes', '#EF4444', NOW(), NOW());

-- Plataformas y Formatos
INSERT INTO plataformas (nombre, icono, color) VALUES
('Facebook', '📘', '#1877F2'),
('Instagram', '📷', '#E4405F'),
('Twitter', '🐦', '#1DA1F2'),
('LinkedIn', '💼', '#0A66C2'),
('TikTok', '🎵', '#000000'),
('YouTube', '📹', '#FF0000');

INSERT INTO formatos (nombre, descripcion) VALUES
('Post', 'Publicación estándar'),
('Story', 'Historia temporal'),
('Reel', 'Video corto'),
('Carrusel', 'Múltiples imágenes'),
('Video', 'Video largo'),
('Live', 'Transmisión en vivo');

-- Publicaciones (30 publicaciones)
INSERT INTO publicaciones (cliente_id, plataforma_id, formato_id, fecha, copy, arte, estatus, created_at, updated_at) VALUES
(1, 1, 1, DATE_SUB(NOW(), INTERVAL 15 DAY), 'Soluciones tecnológicas innovadoras 🚀', 'arte_tech_01.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 20 DAY), DATE_SUB(NOW(), INTERVAL 15 DAY)),
(1, 2, 3, DATE_SUB(NOW(), INTERVAL 10 DAY), 'Tips de productividad 💡', 'video_tips.mp4', 'Publicado', DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY)),
(1, 4, 1, DATE_SUB(NOW(), INTERVAL 5 DAY), 'Caso de éxito', 'caso_exito.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
(2, 2, 3, DATE_SUB(NOW(), INTERVAL 12 DAY), 'Tacos al pastor 🌮', 'video_tacos.mp4', 'Publicado', DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 12 DAY)),
(2, 1, 4, DATE_SUB(NOW(), INTERVAL 8 DAY), 'Menú de la semana 😋', 'carrusel_menu.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 12 DAY), DATE_SUB(NOW(), INTERVAL 8 DAY)),
(2, 2, 2, DATE_SUB(NOW(), INTERVAL 3 DAY), '2x1 en bebidas', 'story_promo.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),
(3, 4, 1, DATE_SUB(NOW(), INTERVAL 14 DAY), 'Consejos empresariales', 'post_consejos.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 18 DAY), DATE_SUB(NOW(), INTERVAL 14 DAY)),
(3, 1, 1, DATE_SUB(NOW(), INTERVAL 7 DAY), 'Webinar gratuito', 'webinar.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY)),
(4, 2, 4, DATE_SUB(NOW(), INTERVAL 11 DAY), 'Colección primavera 🌸', 'coleccion.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 11 DAY)),
(4, 2, 3, DATE_SUB(NOW(), INTERVAL 6 DAY), 'Tendencias de moda', 'reel_tendencias.mp4', 'Publicado', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY)),
(4, 1, 1, DATE_SUB(NOW(), INTERVAL 2 DAY), 'Descuento especial', 'descuento.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 5 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
(5, 1, 1, DATE_SUB(NOW(), INTERVAL 9 DAY), 'Herramientas profesionales', 'herramientas.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 12 DAY), DATE_SUB(NOW(), INTERVAL 9 DAY)),
(5, 2, 1, DATE_SUB(NOW(), INTERVAL 4 DAY), 'Tips mantenimiento 🔧', 'tips_hogar.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 8 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY)),
(6, 2, 3, DATE_SUB(NOW(), INTERVAL 13 DAY), 'Rutina principiantes', 'rutina_video.mp4', 'Publicado', DATE_SUB(NOW(), INTERVAL 16 DAY), DATE_SUB(NOW(), INTERVAL 13 DAY)),
(6, 1, 1, DATE_SUB(NOW(), INTERVAL 8 DAY), 'Promoción 3x2', 'promo_gym.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 11 DAY), DATE_SUB(NOW(), INTERVAL 8 DAY)),
(7, 2, 3, DATE_SUB(NOW(), INTERVAL 10 DAY), 'Café perfecto ☕', 'video_cafe.mp4', 'Publicado', DATE_SUB(NOW(), INTERVAL 13 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY)),
(7, 2, 2, DATE_SUB(NOW(), INTERVAL 5 DAY), 'Espresso con chocolate', 'story_espresso.jpg', 'Publicado', DATE_SUB(NOW(), INTERVAL 8 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
(1, 2, 1, DATE_ADD(NOW(), INTERVAL 2 DAY), 'Nuevo servicio cloud', 'nuevo_servicio.jpg', 'Pendiente', NOW(), NOW()),
(2, 2, 3, DATE_ADD(NOW(), INTERVAL 3 DAY), 'Receta especial', 'receta_especial.mp4', 'Pendiente', NOW(), NOW()),
(3, 4, 1, DATE_ADD(NOW(), INTERVAL 4 DAY), 'Transformación digital', 'articulo_digital.jpg', 'Pendiente', NOW(), NOW()),
(4, 2, 4, DATE_ADD(NOW(), INTERVAL 5 DAY), 'Lookbook primavera', 'lookbook.jpg', 'Pendiente', NOW(), NOW()),
(5, 1, 1, DATE_ADD(NOW(), INTERVAL 6 DAY), 'Ofertas de la semana', 'ofertas_semana.jpg', 'Pendiente', NOW(), NOW()),
(6, 2, 3, DATE_ADD(NOW(), INTERVAL 7 DAY), 'Ejercicios core', 'ejercicios_core.mp4', 'Pendiente', NOW(), NOW()),
(7, 2, 1, DATE_ADD(NOW(), INTERVAL 8 DAY), 'Nuevo menú postres', 'menu_postres.jpg', 'Pendiente', NOW(), NOW()),
(1, 1, 1, DATE_SUB(NOW(), INTERVAL 1 DAY), 'Innovación tecnológica', 'innovacion.jpg', 'Reprogramar', DATE_SUB(NOW(), INTERVAL 5 DAY), NOW()),
(2, 2, 2, NOW(), 'Platillo especial', 'platillo_especial.jpg', 'Reprogramar', DATE_SUB(NOW(), INTERVAL 3 DAY), NOW()),
(4, 2, 3, DATE_ADD(NOW(), INTERVAL 1 DAY), 'Tendencias moda', 'tendencias_reel.mp4', 'Reprogramar', DATE_SUB(NOW(), INTERVAL 2 DAY), NOW()),
(6, 1, 1, DATE_ADD(NOW(), INTERVAL 2 DAY), 'Testimonial cliente', 'testimonial.jpg', 'Reprogramar', DATE_SUB(NOW(), INTERVAL 1 DAY), NOW()),
(7, 2, 1, DATE_ADD(NOW(), INTERVAL 3 DAY), 'Café de especialidad', 'cafe_mes.jpg', 'Reprogramar', NOW(), NOW()),
(3, 2, 1, DATE_ADD(NOW(), INTERVAL 9 DAY), 'Consultoría empresarial', 'consultoria.jpg', 'Pendiente', NOW(), NOW());
