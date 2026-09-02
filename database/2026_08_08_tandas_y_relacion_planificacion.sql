-- Script: tabla tandas + relacion con planificacion academica
-- Fecha: 2026-08-08

START TRANSACTION;

-- 1) Crear tabla de tandas
CREATE TABLE IF NOT EXISTS `tandas` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(100) NOT NULL,
    `codigo` VARCHAR(30) NOT NULL,
    `hora_inicio` TIME NULL,
    `hora_fin` TIME NULL,
    `estado` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_tandas_nombre` (`nombre`),
    UNIQUE KEY `uq_tandas_codigo` (`codigo`),
    KEY `idx_tandas_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2) Cargar tandas base (si no existen)
INSERT IGNORE INTO `tandas` (`nombre`, `codigo`, `hora_inicio`, `hora_fin`, `estado`)
VALUES
    ('Matutino', 'MATUTINO', '07:00:00', '12:00:00', 1),
    ('Vespertino', 'VESPERTINO', '13:00:00', '18:00:00', 1);

-- 3) Relacionar planificaciones_academicas con tanda
ALTER TABLE `planificaciones_academicas`
    ADD COLUMN `tanda_id` BIGINT UNSIGNED NULL AFTER `seccion_id`;

-- 4) Migracion de datos existentes usando columna jornada
UPDATE `planificaciones_academicas` pa
INNER JOIN `tandas` t ON t.`codigo` = pa.`jornada`
SET pa.`tanda_id` = t.`id`
WHERE pa.`tanda_id` IS NULL;

-- 5) Indices y llave foranea
ALTER TABLE `planificaciones_academicas`
    ADD KEY `idx_planif_tanda_id` (`tanda_id`),
    ADD CONSTRAINT `fk_planif_tanda`
        FOREIGN KEY (`tanda_id`) REFERENCES `tandas` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT;

-- 6) Integridad sugerida: asegurar combinacion unica incluyendo tanda
ALTER TABLE `planificaciones_academicas`
    ADD UNIQUE KEY `uq_planif_anio_nivel_grado_seccion_tanda`
        (`anio_escolar_id`, `nivel_id`, `grado_id`, `seccion_id`, `tanda_id`);

COMMIT;

-- Nota:
-- Cuando migres completamente a tanda_id, puedes retirar la columna jornada
-- y ajustar el modelo PlanificacionesAcademicasModel para usar tanda_id en lugar de jornada.
