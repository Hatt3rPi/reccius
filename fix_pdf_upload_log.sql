-- Script para corregir registros mal clasificados en pdf_upload_log
-- El problema: bind_param usaba "b" (no válido) en lugar de "i" para boolean
-- Resultado: uploads exitosos se registraron como fallos (exito=0)

-- Corregir todos los registros que fueron exitosos pero se marcaron como fallos
-- Criterio: error_msg y error_type vacíos indican éxito
UPDATE pdf_upload_log
SET exito = 1
WHERE exito = 0
  AND (error_msg = '' OR error_msg IS NULL)
  AND (error_type = '' OR error_type IS NULL);

-- Verificar el resultado
SELECT
    COUNT(*) as total,
    SUM(exito) as exitosos,
    COUNT(*) - SUM(exito) as fallidos,
    ROUND((SUM(exito) * 100.0 / COUNT(*)), 2) as porcentaje_exito
FROM pdf_upload_log;

-- Mostrar últimos 10 registros para verificar
SELECT
    id, usuario, id_solicitud, fecha_hora, exito, error_msg, error_type
FROM pdf_upload_log
ORDER BY id DESC
LIMIT 10;