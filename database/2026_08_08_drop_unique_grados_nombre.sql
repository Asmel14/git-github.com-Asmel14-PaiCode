-- Script: quitar restriccion unica por nombre en grados
-- Fecha: 2026-08-08

START TRANSACTION;

-- Mantener id como unica clave primaria y permitir nombres repetidos.
SET @idx_exists := (
    SELECT COUNT(*)
    FROM information_schema.statistics
    WHERE table_schema = DATABASE()
      AND table_name = 'grados'
      AND index_name = 'uk_grado_nombre'
);

SET @sql := IF(
    @idx_exists > 0,
    'ALTER TABLE `grados` DROP INDEX `uk_grado_nombre`',
    'SELECT ''Indice uk_grado_nombre no existe, nada que cambiar'''
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

COMMIT;
