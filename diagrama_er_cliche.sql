-- ============================================================================
-- DIAGRAMA ENTIDAD-RELACIÓN - SISTEMA CLICHÉ MARKETING DIGITAL
-- Script para MySQL Workbench
-- Incluye TODAS las relaciones físicas y lógicas del sistema
-- ============================================================================

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- ============================================================================
-- SCHEMA: cliche
-- ============================================================================
CREATE SCHEMA IF NOT EXISTS `cliche_diagrama` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cliche_diagrama`;

-- ============================================================================
-- MÓDULO: AUTENTICACIÓN Y USUARIOS
-- ============================================================================

-- -----------------------------------------------------------------------------
-- Tabla: users
-- Descripción: Usuarios del sistema (administradores, empleados y clientes)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del usuario',
  `email` VARCHAR(191) NOT NULL COMMENT 'Correo electrónico (único)',
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Fecha de verificación del email',
  `password` VARCHAR(191) NOT NULL COMMENT 'Contraseña encriptada',
  `rol` ENUM('admin','empleado','cliente') NOT NULL DEFAULT 'cliente' COMMENT 'Rol del usuario en el sistema',
  `remember_token` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Token para recordar sesión',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `users_email_unique` (`email` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Usuarios del sistema con autenticación';

-- ============================================================================
-- MÓDULO: RECURSOS HUMANOS
-- ============================================================================

-- -----------------------------------------------------------------------------
-- Tabla: empleados
-- Descripción: Empleados de la agencia
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `empleados`;
CREATE TABLE `empleados` (
  `id_empleado` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del empleado',
  `nombre` VARCHAR(120) NOT NULL COMMENT 'Nombre del empleado',
  `apellido_paterno` VARCHAR(120) NOT NULL COMMENT 'Apellido paterno',
  `apellido_materno` VARCHAR(120) NULL DEFAULT NULL COMMENT 'Apellido materno (opcional)',
  `telefono` VARCHAR(30) NOT NULL COMMENT 'Teléfono de contacto',
  `puesto` VARCHAR(120) NOT NULL COMMENT 'Puesto de trabajo',
  `fecha_ingreso` DATE NOT NULL COMMENT 'Fecha de ingreso a la empresa',
  `estatus` ENUM('activo','inactivo','baja') NOT NULL DEFAULT 'activo' COMMENT 'Estado del empleado',
  `fecha_baja` DATE NULL DEFAULT NULL COMMENT 'Fecha de baja (si aplica)',
  `id_usuario` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Usuario asociado',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id_empleado`),
  INDEX `ix_empleado_usuario` (`id_usuario` ASC),
  INDEX `ix_empleado_estatus` (`estatus` ASC),
  CONSTRAINT `fk_empleados_users`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `users` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Empleados de la agencia';

-- ============================================================================
-- MÓDULO: CLIENTES
-- ============================================================================

-- -----------------------------------------------------------------------------
-- Tabla: clientes
-- Descripción: Clientes de la agencia
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `id_cliente` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del cliente',
  `empresa` VARCHAR(180) NULL DEFAULT NULL COMMENT 'Nombre de la empresa (opcional)',
  `nombre` VARCHAR(120) NOT NULL COMMENT 'Nombre del cliente',
  `apellido_paterno` VARCHAR(120) NOT NULL COMMENT 'Apellido paterno',
  `apellido_materno` VARCHAR(120) NULL DEFAULT NULL COMMENT 'Apellido materno (opcional)',
  `giro_sector` VARCHAR(150) NOT NULL COMMENT 'Giro o sector del negocio',
  `telefono` VARCHAR(30) NOT NULL COMMENT 'Teléfono de contacto',
  `estatus` ENUM('activo','inactivo') NOT NULL DEFAULT 'activo' COMMENT 'Estado del cliente',
  `fecha_registro` DATE NOT NULL COMMENT 'Fecha de alta en el sistema',
  `fecha_baja` DATE NULL DEFAULT NULL COMMENT 'Fecha de baja (si aplica)',
  `id_usuario` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Usuario asociado',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  INDEX `clientes_id_usuario_foreign` (`id_usuario` ASC),
  INDEX `ix_clientes_estatus` (`estatus` ASC),
  INDEX `ix_clientes_registro` (`fecha_registro` ASC),
  INDEX `ix_clientes_baja` (`fecha_baja` ASC),
  CONSTRAINT `fk_clientes_users`
    FOREIGN KEY (`id_usuario`)
    REFERENCES `users` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Clientes de la agencia';

-- -----------------------------------------------------------------------------
-- Tabla: info_fiscal
-- Descripción: Información fiscal de los clientes
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `info_fiscal`;
CREATE TABLE `info_fiscal` (
  `id_fiscal` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del registro fiscal',
  `id_cliente` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Cliente asociado',
  `rfc` VARCHAR(20) NOT NULL COMMENT 'RFC del cliente',
  `razon_social` VARCHAR(255) NOT NULL COMMENT 'Razón social',
  `direccion_fiscal` VARCHAR(400) NOT NULL COMMENT 'Dirección fiscal completa',
  `regimen` VARCHAR(120) NOT NULL COMMENT 'Régimen fiscal',
  `telefono_fiscal` VARCHAR(30) NOT NULL COMMENT 'Teléfono fiscal',
  `correo_fiscal` VARCHAR(255) NOT NULL COMMENT 'Correo fiscal',
  `fecha_registro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro',
  `fecha_actualiza` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Última actualización',
  PRIMARY KEY (`id_fiscal`),
  INDEX `info_fiscal_rfc_index` (`rfc` ASC),
  INDEX `fk_info_fiscal_cliente_idx` (`id_cliente` ASC),
  CONSTRAINT `fk_info_fiscal_clientes`
    FOREIGN KEY (`id_cliente`)
    REFERENCES `clientes` (`id_cliente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Información fiscal de clientes para facturación';

-- -----------------------------------------------------------------------------
-- Tabla: bitacora_clientes
-- Descripción: Registro de movimientos de clientes (alta/baja/reactivación)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `bitacora_clientes`;
CREATE TABLE `bitacora_clientes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del movimiento',
  `id_cliente` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Cliente afectado',
  `accion` VARCHAR(191) NOT NULL COMMENT 'Tipo de acción (alta, baja, reactivacion)',
  `id_usuario_responsable` BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'FK: Usuario que realizó la acción',
  `fecha_movimiento` DATETIME NOT NULL COMMENT 'Fecha y hora del movimiento',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `bitacora_clientes_id_cliente_foreign` (`id_cliente` ASC),
  INDEX `bitacora_clientes_id_usuario_responsable_foreign` (`id_usuario_responsable` ASC),
  CONSTRAINT `fk_bitacora_clientes_clientes`
    FOREIGN KEY (`id_cliente`)
    REFERENCES `clientes` (`id_cliente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_bitacora_clientes_users`
    FOREIGN KEY (`id_usuario_responsable`)
    REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Bitácora de movimientos de clientes para reportes';

-- ============================================================================
-- MÓDULO: COTIZACIONES
-- ============================================================================

-- -----------------------------------------------------------------------------
-- Tabla: cotizaciones
-- Descripción: Cotizaciones emitidas a clientes
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `cotizaciones`;
CREATE TABLE `cotizaciones` (
  `id_cotizacion` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único de la cotización',
  `id_cliente` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Cliente al que se cotiza',
  `titulo_cotizacion` VARCHAR(200) NOT NULL COMMENT 'Título de la cotización',
  `texto_introduccion` TEXT NULL DEFAULT NULL COMMENT 'Texto introductorio (opcional)',
  `fecha` DATE NOT NULL COMMENT 'Fecha de emisión',
  `vencimiento_dias` TINYINT UNSIGNED NOT NULL DEFAULT 7 COMMENT 'Días de vigencia',
  `subtotal` DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Subtotal sin IVA',
  `iva_total` DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'IVA total',
  `porcentaje_isr` DECIMAL(5,2) NOT NULL DEFAULT 0.00 COMMENT 'Porcentaje de ISR',
  `retencion_isr` DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Retención de ISR',
  `total` DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Total de la cotización',
  `notas` TEXT NULL DEFAULT NULL COMMENT 'Notas adicionales (opcional)',
  `estatus` ENUM('pendiente','aceptada','rechazada') NOT NULL DEFAULT 'pendiente' COMMENT 'Estado de la cotización',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id_cotizacion`),
  INDEX `ix_cot_cliente_fecha` (`id_cliente` ASC, `fecha` ASC),
  INDEX `ix_cot_estatus` (`estatus` ASC),
  CONSTRAINT `fk_cotizaciones_clientes`
    FOREIGN KEY (`id_cliente`)
    REFERENCES `clientes` (`id_cliente`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Cotizaciones emitidas a clientes';

-- -----------------------------------------------------------------------------
-- Tabla: cotizacion_detalle
-- Descripción: Partidas/servicios de cada cotización
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `cotizacion_detalle`;
CREATE TABLE `cotizacion_detalle` (
  `id_detalle` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del detalle',
  `id_cotizacion` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Cotización a la que pertenece',
  `orden` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Orden de visualización',
  `titulo` VARCHAR(100) NOT NULL COMMENT 'Título del servicio/partida',
  `cantidad` DECIMAL(10,2) NOT NULL DEFAULT 1.00 COMMENT 'Cantidad',
  `descripcion` VARCHAR(400) NOT NULL COMMENT 'Descripción del servicio',
  `precio_unitario` DECIMAL(12,2) NOT NULL COMMENT 'Precio unitario sin IVA',
  `iva` DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'IVA de la partida',
  `precio_total` DECIMAL(12,2) NOT NULL COMMENT 'Precio total de la partida',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id_detalle`),
  INDEX `ix_detalle_cot` (`id_cotizacion` ASC),
  CONSTRAINT `fk_cotizacion_detalle_cotizaciones`
    FOREIGN KEY (`id_cotizacion`)
    REFERENCES `cotizaciones` (`id_cotizacion`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Detalles/partidas de cotizaciones';

-- ============================================================================
-- MÓDULO: GESTIÓN DE TAREAS
-- ============================================================================

-- -----------------------------------------------------------------------------
-- Tabla: categorias
-- Descripción: Categorías de tareas (Diseño, Desarrollo, Redes Sociales, etc.)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único de la categoría',
  `nombre` VARCHAR(100) NOT NULL COMMENT 'Nombre de la categoría',
  `descripcion` TEXT NULL DEFAULT NULL COMMENT 'Descripción (opcional)',
  `activo` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Estado activo/inactivo',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Categorías de tareas';

-- -----------------------------------------------------------------------------
-- Tabla: tareas
-- Descripción: Tareas maestras asociadas a clientes
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `tareas`;
CREATE TABLE `tareas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único de la tarea',
  `titulo` VARCHAR(200) NOT NULL COMMENT 'Título de la tarea',
  `cliente_id` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Cliente asociado',
  `categoria_id` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Categoría de la tarea',
  `descripcion` TEXT NOT NULL COMMENT 'Descripción completa',
  `observaciones` TEXT NULL DEFAULT NULL COMMENT 'Observaciones adicionales (opcional)',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `tareas_categoria_id_foreign` (`categoria_id` ASC),
  INDEX `tareas_cliente_id_foreign` (`cliente_id` ASC),
  CONSTRAINT `fk_tareas_categorias`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `categorias` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tareas_clientes`
    FOREIGN KEY (`cliente_id`)
    REFERENCES `clientes` (`id_cliente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tareas maestras del sistema';

-- -----------------------------------------------------------------------------
-- Tabla: asignaciones_tareas
-- Descripción: Asignaciones de tareas a empleados (relación many-to-many)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `asignaciones_tareas`;
CREATE TABLE `asignaciones_tareas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único de la asignación',
  `tarea_id` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Tarea asignada',
  `empleado_id` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Empleado asignado',
  `prioridad` ENUM('baja','media','alta','urgente') NOT NULL DEFAULT 'media' COMMENT 'Prioridad de la asignación',
  `fecha_limite` DATE NOT NULL COMMENT 'Fecha límite de entrega',
  `estado_empleado` ENUM('asignada','en_proceso','terminada') NOT NULL DEFAULT 'asignada' COMMENT 'Estado desde el empleado',
  `estado_admin` ENUM('pendiente','completa','parcialmente_completa','incompleta') NULL DEFAULT NULL COMMENT 'Evaluación del admin',
  `evidencia_path` VARCHAR(191) NULL DEFAULT NULL COMMENT 'Ruta del archivo de evidencia (PDF)',
  `fecha_entrega` TIMESTAMP NULL DEFAULT NULL COMMENT 'Fecha/hora de entrega real',
  `notas_admin` TEXT NULL DEFAULT NULL COMMENT 'Notas del administrador (opcional)',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `asignaciones_tareas_tarea_id_foreign` (`tarea_id` ASC),
  INDEX `asignaciones_tareas_empleado_id_foreign` (`empleado_id` ASC),
  CONSTRAINT `fk_asignaciones_tareas_tareas`
    FOREIGN KEY (`tarea_id`)
    REFERENCES `tareas` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_asignaciones_tareas_empleados`
    FOREIGN KEY (`empleado_id`)
    REFERENCES `empleados` (`id_empleado`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Asignaciones de tareas a empleados';

-- ============================================================================
-- MÓDULO: NOTIFICACIONES
-- ============================================================================

-- -----------------------------------------------------------------------------
-- Tabla: notificaciones
-- Descripción: Notificaciones del sistema para usuarios
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `notificaciones`;
CREATE TABLE `notificaciones` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único de la notificación',
  `user_id` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Usuario destinatario',
  `asignacion_tarea_id` BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'FK: Asignación relacionada (opcional)',
  `tipo` ENUM('tarea_asignada','tarea_en_proceso','tarea_terminada','tarea_evaluada','tarea_evaluada_completa','tarea_evaluada_parcial','tarea_evaluada_incompleta','recordatorio_evento','alerta') NOT NULL COMMENT 'Tipo de notificación',
  `titulo` VARCHAR(191) NOT NULL COMMENT 'Título de la notificación',
  `mensaje` TEXT NOT NULL COMMENT 'Mensaje completo',
  `url` VARCHAR(191) NULL DEFAULT NULL COMMENT 'URL destino (opcional)',
  `leida` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Estado leída/no leída',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `notificaciones_user_id_foreign` (`user_id` ASC),
  INDEX `notificaciones_asignacion_tarea_id_foreign` (`asignacion_tarea_id` ASC),
  CONSTRAINT `fk_notificaciones_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_notificaciones_asignaciones`
    FOREIGN KEY (`asignacion_tarea_id`)
    REFERENCES `asignaciones_tareas` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Notificaciones del sistema';

-- ============================================================================
-- MÓDULO: SUSCRIPCIONES
-- ============================================================================

-- -----------------------------------------------------------------------------
-- Tabla: categorias_suscripcion
-- Descripción: Categorías de suscripciones (Software, Hosting, IA, etc.)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `categorias_suscripcion`;
CREATE TABLE `categorias_suscripcion` (
  `idCategoria` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único de la categoría',
  `nombre` VARCHAR(100) NOT NULL COMMENT 'Nombre de la categoría',
  `estatus` ENUM('activo','inactivo') NOT NULL DEFAULT 'activo' COMMENT 'Estado de la categoría',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`idCategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Categorías de suscripciones de servicios';

-- -----------------------------------------------------------------------------
-- Tabla: suscripciones
-- Descripción: Suscripciones de servicios de la agencia
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `suscripciones`;
CREATE TABLE `suscripciones` (
  `idSuscripcion` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único de la suscripción',
  `idCategoria` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Categoría de suscripción',
  `nombre_servicio` VARCHAR(150) NOT NULL COMMENT 'Nombre del servicio',
  `fecha_inicio` DATE NOT NULL COMMENT 'Fecha de inicio del servicio',
  `costo` DECIMAL(10,2) NOT NULL COMMENT 'Costo del servicio',
  `periodicidad` ENUM('mensual','anual') NOT NULL COMMENT 'Periodicidad de pago',
  `fecha_vencimiento` DATE NOT NULL COMMENT 'Fecha de vencimiento',
  `dias_recordatorio` JSON NULL DEFAULT NULL COMMENT 'Días de anticipación para recordatorios',
  `nivel_uso` ENUM('bajo','medio','alto') NOT NULL DEFAULT 'medio' COMMENT 'Nivel de uso del servicio',
  `observaciones` TEXT NULL DEFAULT NULL COMMENT 'Observaciones (opcional)',
  `estatus` ENUM('activo','inactivo') NOT NULL DEFAULT 'activo' COMMENT 'Estado de la suscripción',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`idSuscripcion`),
  INDEX `suscripciones_idcategoria_foreign` (`idCategoria` ASC),
  CONSTRAINT `fk_suscripciones_categorias`
    FOREIGN KEY (`idCategoria`)
    REFERENCES `categorias_suscripcion` (`idCategoria`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Suscripciones de servicios (Adobe, Canva, Hosting, etc.)';

-- -----------------------------------------------------------------------------
-- Tabla: suscripcion_renovaciones
-- Descripción: Historial de renovaciones de suscripciones
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `suscripcion_renovaciones`;
CREATE TABLE `suscripcion_renovaciones` (
  `idRenovacion` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único de la renovación',
  `idSuscripcion` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Suscripción renovada',
  `fecha_renovacion` DATE NOT NULL COMMENT 'Fecha de renovación',
  `costo_ciclo` DECIMAL(10,2) NOT NULL COMMENT 'Costo del ciclo renovado',
  `fecha_vencimiento_anterior` DATE NOT NULL COMMENT 'Vencimiento anterior',
  `fecha_vencimiento_nueva` DATE NOT NULL COMMENT 'Nuevo vencimiento',
  `observaciones` TEXT NULL DEFAULT NULL COMMENT 'Observaciones (opcional)',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`idRenovacion`),
  INDEX `suscripcion_renovaciones_idsuscripcion_foreign` (`idSuscripcion` ASC),
  CONSTRAINT `fk_renovaciones_suscripciones`
    FOREIGN KEY (`idSuscripcion`)
    REFERENCES `suscripciones` (`idSuscripcion`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Historial de renovaciones de suscripciones';

-- -----------------------------------------------------------------------------
-- Tabla: suscripcion_recordatorios_log
-- Descripción: Log de recordatorios enviados para suscripciones
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `suscripcion_recordatorios_log`;
CREATE TABLE `suscripcion_recordatorios_log` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del log',
  `idSuscripcion` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Suscripción del recordatorio',
  `fecha_recordatorio` DATE NOT NULL COMMENT 'Fecha del recordatorio',
  `dias_restantes` INT NOT NULL COMMENT 'Días restantes para vencimiento',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `sus_rec_log_unique` (`idSuscripcion` ASC, `fecha_recordatorio` ASC),
  CONSTRAINT `fk_recordatorios_log_suscripciones`
    FOREIGN KEY (`idSuscripcion`)
    REFERENCES `suscripciones` (`idSuscripcion`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Log de recordatorios de vencimiento';

-- ============================================================================
-- MÓDULO: MINUTAS Y ACUERDOS
-- ============================================================================

-- -----------------------------------------------------------------------------
-- Tabla: minutas
-- Descripción: Minutas de reuniones con clientes
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `minutas`;
CREATE TABLE `minutas` (
  `id_minuta` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único de la minuta',
  `id_cliente` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Cliente de la reunión',
  `titulo` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Título de la minuta (opcional)',
  `fecha` DATE NOT NULL COMMENT 'Fecha de la reunión',
  `asistentes` VARCHAR(500) NULL DEFAULT NULL COMMENT 'Asistentes (HTML, opcional)',
  `puntos_tratados` LONGTEXT NULL DEFAULT NULL COMMENT 'Puntos tratados (HTML, opcional)',
  `observaciones` LONGTEXT NULL DEFAULT NULL COMMENT 'Observaciones (HTML, opcional)',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id_minuta`),
  INDEX `minutas_id_cliente_foreign` (`id_cliente` ASC),
  CONSTRAINT `fk_minutas_clientes`
    FOREIGN KEY (`id_cliente`)
    REFERENCES `clientes` (`id_cliente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Minutas de reuniones con clientes';

-- -----------------------------------------------------------------------------
-- Tabla: acuerdos
-- Descripción: Acuerdos derivados de minutas
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `acuerdos`;
CREATE TABLE `acuerdos` (
  `id_acuerdo` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del acuerdo',
  `id_minuta` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Minuta a la que pertenece',
  `acuerdo` LONGTEXT NOT NULL COMMENT 'Descripción del acuerdo',
  `responsable` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Responsable del acuerdo (opcional)',
  `orden` INT NOT NULL DEFAULT 0 COMMENT 'Orden de visualización',
  `estatus` ENUM('pendiente','completado','cancelado') NOT NULL DEFAULT 'pendiente' COMMENT 'Estado del acuerdo',
  `fecha_limite` DATE NULL DEFAULT NULL COMMENT 'Fecha límite (opcional)',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id_acuerdo`),
  INDEX `acuerdos_id_minuta_foreign` (`id_minuta` ASC),
  CONSTRAINT `fk_acuerdos_minutas`
    FOREIGN KEY (`id_minuta`)
    REFERENCES `minutas` (`id_minuta`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Acuerdos derivados de minutas';

-- ============================================================================
-- MÓDULO: BRIEFS
-- ============================================================================

-- -----------------------------------------------------------------------------
-- Tabla: briefs
-- Descripción: Formularios de briefs (Google Forms)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `briefs`;
CREATE TABLE `briefs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del brief',
  `google_form_id` VARCHAR(191) NOT NULL COMMENT 'ID del formulario de Google',
  `titulo` VARCHAR(191) NOT NULL COMMENT 'Título del brief',
  `descripcion` TEXT NULL DEFAULT NULL COMMENT 'Descripción (opcional)',
  `form_url` VARCHAR(191) NULL DEFAULT NULL COMMENT 'URL del formulario (opcional)',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `briefs_google_form_id_unique` (`google_form_id` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Formularios de briefs (Google Forms)';

-- -----------------------------------------------------------------------------
-- Tabla: brief_cliente
-- Descripción: Asignación de briefs a clientes (relación many-to-many)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `brief_cliente`;
CREATE TABLE `brief_cliente` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único de la asignación',
  `brief_id` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Brief asignado',
  `cliente_id` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Cliente asignado',
  `estado` VARCHAR(191) NOT NULL DEFAULT 'pendiente' COMMENT 'Estado (pendiente, recibido)',
  `google_response_id` VARCHAR(191) NULL DEFAULT NULL COMMENT 'ID de respuesta de Google',
  `fecha_envio` TIMESTAMP NULL DEFAULT NULL COMMENT 'Fecha de envío',
  `fecha_ultimo_recordatorio` TIMESTAMP NULL DEFAULT NULL COMMENT 'Último recordatorio enviado',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `brief_cliente_brief_id_foreign` (`brief_id` ASC),
  INDEX `brief_cliente_cliente_id_foreign` (`cliente_id` ASC),
  CONSTRAINT `fk_brief_cliente_briefs`
    FOREIGN KEY (`brief_id`)
    REFERENCES `briefs` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_brief_cliente_clientes`
    FOREIGN KEY (`cliente_id`)
    REFERENCES `clientes` (`id_cliente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Asignación de briefs a clientes';

-- ============================================================================
-- MÓDULO: EVENTOS Y CALENDARIO
-- ============================================================================

-- -----------------------------------------------------------------------------
-- Tabla: eventos
-- Descripción: Eventos del calendario (reuniones, sesiones, etc.)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `eventos`;
CREATE TABLE `eventos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del evento',
  `titulo` VARCHAR(191) NOT NULL COMMENT 'Título del evento',
  `cliente_id` BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'FK: Cliente relacionado (opcional)',
  `creado_por` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Usuario que creó el evento',
  `fecha` DATE NOT NULL COMMENT 'Fecha del evento',
  `hora_inicio` TIME NOT NULL COMMENT 'Hora de inicio',
  `hora_fin` TIME NOT NULL COMMENT 'Hora de fin',
  `lugar` VARCHAR(191) NULL DEFAULT NULL COMMENT 'Lugar del evento (opcional)',
  `notas` TEXT NULL DEFAULT NULL COMMENT 'Notas adicionales (opcional)',
  `color` VARCHAR(191) NOT NULL DEFAULT '#3B82F6' COMMENT 'Color para el calendario',
  `recurrencia` ENUM('ninguna','diaria','semanal','mensual','anual') NOT NULL DEFAULT 'ninguna' COMMENT 'Tipo de recurrencia',
  `recurrencia_config` JSON NULL DEFAULT NULL COMMENT 'Configuración de recurrencia',
  `recurrencia_hasta` DATE NULL DEFAULT NULL COMMENT 'Fecha límite de recurrencia',
  `google_event_id` VARCHAR(191) NULL DEFAULT NULL COMMENT 'ID del evento en Google Calendar',
  `sincronizado_google` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Sincronizado con Google',
  `ultima_sincronizacion` TIMESTAMP NULL DEFAULT NULL COMMENT 'Última sincronización',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `eventos_google_event_id_unique` (`google_event_id` ASC),
  INDEX `eventos_fecha_hora_inicio_index` (`fecha` ASC, `hora_inicio` ASC),
  INDEX `eventos_cliente_id_index` (`cliente_id` ASC),
  INDEX `eventos_creado_por_index` (`creado_por` ASC),
  CONSTRAINT `fk_eventos_clientes`
    FOREIGN KEY (`cliente_id`)
    REFERENCES `clientes` (`id_cliente`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_eventos_users`
    FOREIGN KEY (`creado_por`)
    REFERENCES `users` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Eventos del calendario';

-- -----------------------------------------------------------------------------
-- Tabla: evento_participantes
-- Descripción: Participantes de eventos
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `evento_participantes`;
CREATE TABLE `evento_participantes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del participante',
  `evento_id` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Evento',
  `tipo` ENUM('cliente','empleado','externo') NOT NULL COMMENT 'Tipo de participante',
  `referencia_id` BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'ID del cliente/empleado (opcional)',
  `nombre` VARCHAR(191) NOT NULL COMMENT 'Nombre del participante',
  `correo` VARCHAR(191) NOT NULL COMMENT 'Correo del participante',
  `confirmado` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Confirmación de asistencia',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `evento_participantes_evento_id_index` (`evento_id` ASC),
  INDEX `evento_participantes_tipo_referencia_id_index` (`tipo` ASC, `referencia_id` ASC),
  CONSTRAINT `fk_evento_participantes_eventos`
    FOREIGN KEY (`evento_id`)
    REFERENCES `eventos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Participantes de eventos';

-- -----------------------------------------------------------------------------
-- Tabla: evento_recordatorios
-- Descripción: Recordatorios configurados para eventos
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `evento_recordatorios`;
CREATE TABLE `evento_recordatorios` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del recordatorio',
  `evento_id` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Evento',
  `tipo_notificacion` ENUM('correo','sistema','ambos') NOT NULL DEFAULT 'ambos' COMMENT 'Tipo de notificación',
  `minutos_antes` INT NOT NULL COMMENT 'Minutos antes del evento',
  `enviado` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Estado de envío',
  `fecha_envio` TIMESTAMP NULL DEFAULT NULL COMMENT 'Fecha de envío',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `evento_recordatorios_evento_id_index` (`evento_id` ASC),
  INDEX `evento_recordatorios_enviado_fecha_envio_index` (`enviado` ASC, `fecha_envio` ASC),
  CONSTRAINT `fk_evento_recordatorios_eventos`
    FOREIGN KEY (`evento_id`)
    REFERENCES `eventos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Recordatorios de eventos';

-- ============================================================================
-- MÓDULO: PUBLICACIONES Y CONTENIDO
-- ============================================================================

-- -----------------------------------------------------------------------------
-- Tabla: plataformas
-- Descripción: Plataformas de redes sociales
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `plataformas`;
CREATE TABLE `plataformas` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único de la plataforma',
  `nombre` VARCHAR(191) NOT NULL COMMENT 'Nombre de la plataforma',
  `icono` VARCHAR(191) NULL DEFAULT NULL COMMENT 'Icono (opcional)',
  `activo` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Estado activo/inactivo',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Plataformas de redes sociales';

-- -----------------------------------------------------------------------------
-- Tabla: formatos
-- Descripción: Formatos de publicaciones (Post, Story, Reel, etc.)
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `formatos`;
CREATE TABLE `formatos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único del formato',
  `nombre` VARCHAR(191) NOT NULL COMMENT 'Nombre del formato',
  `especificaciones` TEXT NULL DEFAULT NULL COMMENT 'Especificaciones técnicas (opcional)',
  `activo` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'Estado activo/inactivo',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Formatos de publicaciones';

-- -----------------------------------------------------------------------------
-- Tabla: publicaciones
-- Descripción: Publicaciones programadas para redes sociales
-- -----------------------------------------------------------------------------
DROP TABLE IF EXISTS `publicaciones`;
CREATE TABLE `publicaciones` (
  `idPublicacion` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID único de la publicación',
  `cliente_id` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Cliente de la publicación',
  `lote_calendario` VARCHAR(36) NULL DEFAULT NULL COMMENT 'UUID del lote de calendario',
  `plataforma_id` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Plataforma destino',
  `formato_id` BIGINT UNSIGNED NOT NULL COMMENT 'FK: Formato de publicación',
  `fecha` DATE NOT NULL COMMENT 'Fecha de publicación',
  `copy` TEXT NULL DEFAULT NULL COMMENT 'Texto de la publicación (opcional)',
  `arte` TEXT NULL DEFAULT NULL COMMENT 'Referencias de arte (opcional)',
  `estatus` ENUM('Pendiente','Publicado','Reprogramar') NOT NULL DEFAULT 'Pendiente' COMMENT 'Estado de la publicación',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`idPublicacion`),
  INDEX `publicaciones_cliente_id_foreign` (`cliente_id` ASC),
  INDEX `publicaciones_plataforma_id_foreign` (`plataforma_id` ASC),
  INDEX `publicaciones_formato_id_foreign` (`formato_id` ASC),
  INDEX `publicaciones_lote_calendario_index` (`lote_calendario` ASC),
  CONSTRAINT `fk_publicaciones_clientes`
    FOREIGN KEY (`cliente_id`)
    REFERENCES `clientes` (`id_cliente`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_publicaciones_plataformas`
    FOREIGN KEY (`plataforma_id`)
    REFERENCES `plataformas` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_publicaciones_formatos`
    FOREIGN KEY (`formato_id`)
    REFERENCES `formatos` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Publicaciones programadas para redes sociales';

-- ============================================================================
-- FIN DEL SCRIPT
-- ============================================================================

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
