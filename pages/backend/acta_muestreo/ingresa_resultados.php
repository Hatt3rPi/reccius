<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

// Validación y saneamiento del ID del análisis externo
$id_acta = isset($_GET['id_acta']) ? intval($_GET['id_acta']) : 0;

//OBTENCIÓN DE DATOS
    // Consulta SQL para obtener los datos del análisis externo y el producto asociado
    $query = "SELECT
                CONCAT(am.numero_acta, '-', LPAD(am.version_acta, 2, '0')) AS numero_acta,
                aex.id AS id_analisis_externo,
                aex.id_especificacion,
                aex.id_producto,
                pr.nombre_producto,
                pr.formato,
                pr.concentracion,
                pr.tipo_producto,
                aex.lote,
                aex.tamano_lote,
                aex.codigo_mastersoft,
                aex.condicion_almacenamiento,
                aex.tamano_muestra,
                aex.tamano_contramuestra,
                aex.tipo_analisis,
                usrRev.nombre AS nombre_usrRev,
                usrRev.cargo AS cargo_usrRev,
                usrRev.foto_firma AS foto_firma_usrRev,
                usrRev.ruta_registroPrestadoresSalud AS ruta_registroPrestadoresSalud_usrRev,
                usrMuest.nombre AS nombre_usrMuest,
                usrMuest.cargo AS cargo_usrMuest,
                usrMuest.foto_firma AS foto_firma_usrMuest,
                usrMuest.ruta_registroPrestadoresSalud AS ruta_registroPrestadoresSalud_usrMuest,
                usrEjec.nombre AS nombre_usrEjec,
                usrEjec.cargo AS cargo_usrEjec,
                usrEjec.foto_firma AS foto_firma_usrEjec,
                usrEjec.ruta_registroPrestadoresSalud AS ruta_registroPrestadoresSalud_usrEjec,
                LPAD(pr.identificador_producto,3,'0') AS identificador_producto,
                aex.muestreado_por,
                aex.revisado_por,
                am.responsable,
                am.verificador,
                am.ejecutor,
                am.fecha_firma_responsable,
                am.fecha_firma_ejecutor,
                am.fecha_firma_verificador
            FROM calidad_acta_muestreo AS am
            LEFT JOIN `calidad_analisis_externo` AS aex ON am.id_analisisExterno = aex.id
            LEFT JOIN calidad_productos AS pr ON aex.id_producto = pr.id
            LEFT JOIN usuarios AS usrMuest ON am.responsable = usrMuest.usuario
            LEFT JOIN usuarios AS usrEjec ON am.ejecutor = usrEjec.usuario
            LEFT JOIN usuarios AS usrRev ON am.verificador = usrRev.usuario
            WHERE am.id = ?;";

    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_acta);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $analisis_externos = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $analisis_externos[] = [
            'id_analisis_externo' => $row['id_analisis_externo'],
            'id_especificacion' => $row['id_especificacion'],
            'id_producto' => $row['id_producto'],
            'nombre_producto' => $row['nombre_producto'],
            'formato' => $row['formato'],
            'concentracion' => $row['concentracion'],
            'tipo_producto' => $row['tipo_producto'],
            'lote' => $row['lote'],
            'tamano_lote' => $row['tamano_lote'],
            'codigo_mastersoft' => $row['codigo_mastersoft'],
            'condicion_almacenamiento' => $row['condicion_almacenamiento'],
            'tamano_muestra' => $row['tamano_muestra'],
            'tamano_contramuestra' => $row['tamano_contramuestra'],
            'tipo_analisis' => $row['tipo_analisis'],
            'muestreado_por' => $row['nombre_usrMuest'],
            'cargo_muestreado_por' => $row['cargo_usrMuest'],
            'foto_firma_muestreado_por' => $row['foto_firma_usrMuest'],
            'ruta_registroPrestadoresSalud_muestreado_por' => $row['ruta_registroPrestadoresSalud_usrMuest'],
            'revisado_por' => $row['nombre_usrRev'],
            'cargo_revisado_por' => $row['cargo_usrRev'],
            'foto_firma_revisado_por' => $row['foto_firma_usrRev'],
            'identificador_producto' => $row['identificador_producto'],
            'ruta_registroPrestadoresSalud_revisado_por' => $row['ruta_registroPrestadoresSalud_usrRev'],
            'numero_acta' => $row['numero_acta'],
            'responsable' => $row['responsable'],
            'ejecutor' => $row['ejecutor'],
            'verificador' => $row['verificador'],
            'fecha_firma_responsable' => $row['fecha_firma_responsable'],
            'fecha_firma_ejecutor' => $row['fecha_firma_ejecutor'],
            'fecha_firma_verificador' => $row['fecha_firma_verificador'],
        ];
        
    }
    mysqli_stmt_close($stmt);
    mysqli_close($link);

// Devolver los resultados en formato JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['analisis_externos' => $analisis_externos], JSON_UNESCAPED_UNICODE);
?>
