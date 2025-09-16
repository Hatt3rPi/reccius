-- Tabla para monitoreo de subidas de PDF
-- Archivo: create_pdf_upload_table.sql

CREATE TABLE IF NOT EXISTS pdf_upload_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(100) NOT NULL,
    id_solicitud INT,
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    tamaño_archivo INT,
    exito BOOLEAN NOT NULL,
    error_msg TEXT,
    error_type VARCHAR(50),
    tiempo_respuesta_ms INT,
    INDEX idx_usuario (usuario),
    INDEX idx_fecha (fecha_hora),
    INDEX idx_exito (exito)
);

-- Insertar un registro de ejemplo para verificar la tabla
INSERT INTO pdf_upload_log (usuario, id_solicitud, tamaño_archivo, exito, error_msg, tiempo_respuesta_ms)
VALUES ('test_user', 1, 12345, TRUE, NULL, 1500);