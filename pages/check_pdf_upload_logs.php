<?php
// Script para revisar logs de subida de PDF
// Archivo: check_pdf_upload_logs.php

session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// TEMPORAL: Comentado para debugging - restaurar después de detectar el error de sesión
// Verificar que el usuario tiene permisos (solo administradores)
// $usuarios_permitidos = ['isumonte', 'isumonte@reccius.cl', 'fabarca212@gmail.com'];
// if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario'], $usuarios_permitidos)) {
//     header("Location: login.html");
//     exit;
// }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs de Subida de PDF</title>
    <link rel="stylesheet" href="../assets/css/Listados.css">
    <style>
        .error-row { background-color: #ffebee; }
        .success-row { background-color: #e8f5e8; }
        .stats { margin: 20px 0; padding: 15px; background: #f5f5f5; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Logs de Subida de PDF - Diagnóstico</h1>

        <?php
        // Verificar si la tabla existe
        $check_table = "SHOW TABLES LIKE 'pdf_upload_log'";
        $result = mysqli_query($link, $check_table);

        if (mysqli_num_rows($result) == 0) {
            echo "<div class='alert alert-warning'>La tabla pdf_upload_log no existe. Ejecute el script create_pdf_upload_table.sql primero.</div>";
        } else {
            // Mostrar estadísticas
            $stats_query = "SELECT
                COUNT(*) as total,
                SUM(CASE WHEN exito = 1 THEN 1 ELSE 0 END) as exitosos,
                SUM(CASE WHEN exito = 0 THEN 1 ELSE 0 END) as fallidos,
                AVG(tiempo_respuesta_ms) as tiempo_promedio,
                MAX(fecha_hora) as ultimo_intento
                FROM pdf_upload_log";

            $stats_result = mysqli_query($link, $stats_query);
            $stats = mysqli_fetch_assoc($stats_result);

            echo "<div class='stats'>";
            echo "<h3>Estadísticas de Subidas de PDF</h3>";
            echo "<p><strong>Total intentos:</strong> " . $stats['total'] . "</p>";
            echo "<p><strong>Exitosos:</strong> " . $stats['exitosos'] . "</p>";
            echo "<p><strong>Fallidos:</strong> " . $stats['fallidos'] . "</p>";
            if ($stats['total'] > 0) {
                $porcentaje_exito = round(($stats['exitosos'] / $stats['total']) * 100, 1);
                echo "<p><strong>Porcentaje de éxito:</strong> " . $porcentaje_exito . "%</p>";
            }
            echo "<p><strong>Tiempo promedio:</strong> " . round($stats['tiempo_promedio'], 2) . " ms</p>";
            echo "<p><strong>Último intento:</strong> " . $stats['ultimo_intento'] . "</p>";
            echo "</div>";

            // Mostrar logs recientes
            $logs_query = "SELECT * FROM pdf_upload_log ORDER BY fecha_hora DESC LIMIT 50";
            $logs_result = mysqli_query($link, $logs_query);

            if (mysqli_num_rows($logs_result) > 0) {
                echo "<h3>Últimos 50 Intentos de Subida</h3>";
                echo "<table class='table table-striped'>";
                echo "<thead><tr>
                    <th>Fecha/Hora</th>
                    <th>Usuario</th>
                    <th>ID Solicitud</th>
                    <th>Tamaño (bytes)</th>
                    <th>Éxito</th>
                    <th>Error</th>
                    <th>Tipo Error</th>
                    <th>Tiempo (ms)</th>
                </tr></thead>";
                echo "<tbody>";

                while ($row = mysqli_fetch_assoc($logs_result)) {
                    $row_class = $row['exito'] ? 'success-row' : 'error-row';
                    echo "<tr class='{$row_class}'>";
                    echo "<td>" . $row['fecha_hora'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['usuario']) . "</td>";
                    echo "<td>" . $row['id_solicitud'] . "</td>";
                    echo "<td>" . number_format($row['tamaño_archivo']) . "</td>";
                    echo "<td>" . ($row['exito'] ? '✅' : '❌') . "</td>";
                    echo "<td>" . htmlspecialchars($row['error_msg']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['error_type']) . "</td>";
                    echo "<td>" . $row['tiempo_respuesta_ms'] . "</td>";
                    echo "</tr>";
                }

                echo "</tbody></table>";
            } else {
                echo "<p>No hay logs registrados aún.</p>";
            }

            // Mostrar errores por tipo
            $error_types_query = "SELECT error_type, COUNT(*) as count FROM pdf_upload_log WHERE exito = 0 GROUP BY error_type ORDER BY count DESC";
            $error_types_result = mysqli_query($link, $error_types_query);

            if (mysqli_num_rows($error_types_result) > 0) {
                echo "<h3>Errores por Tipo</h3>";
                echo "<table class='table table-striped'>";
                echo "<thead><tr><th>Tipo de Error</th><th>Cantidad</th></tr></thead>";
                echo "<tbody>";

                while ($row = mysqli_fetch_assoc($error_types_result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['error_type']) . "</td>";
                    echo "<td>" . $row['count'] . "</td>";
                    echo "</tr>";
                }

                echo "</tbody></table>";
            }
        }

        // Mostrar logs del archivo de debug si existe
        $debug_log_path = '/tmp/pdf_upload_debug.log';
        if (file_exists($debug_log_path)) {
            echo "<h3>Log de Debug (últimas 50 líneas)</h3>";
            echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 400px; overflow-y: scroll;'>";
            $lines = file($debug_log_path);
            $last_lines = array_slice($lines, -50);
            echo htmlspecialchars(implode('', $last_lines));
            echo "</pre>";
        }
        ?>

    </div>
</body>
</html>