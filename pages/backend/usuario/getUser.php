<?php
// archivo: pages/backend/usuario/getUser.php
require_once "/home/customw2/conexiones/config_reccius.php";
session_start();
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}

header('Content-Type: application/json; charset=utf-8');
$module_id = $_GET['module_id'] ?? null;
$nombre = $_GET['nombre'] ?? null;
if ( $nombre === null  ) {
    echo json_encode(['error' => 'Parameter "nombre" is required']);
    exit;
}

if ( $module_id === null  ) {
    $query = "SELECT id, usuario, nombre, rol_id, cargo, correo FROM usuarios WHERE nombre LIKE '%" . mysqli_real_escape_string($link, $_GET['nombre']) . "%'";
} else {
    // Query para obtener usuarios que NO tienen relación con el módulo especificado
    $query = "SELECT 
                DISTINCT 
                u.id, u.usuario, 
                u.nombre, u.rol_id, 
                u.cargo, u.correo 
              FROM 
                usuarios u 
              WHERE 
                u.nombre LIKE 
                    '%" . mysqli_real_escape_string($link, $nombre) . "%' 
              AND NOT EXISTS (
                  SELECT 1 
                  FROM usuarios_modulos um 
                  WHERE um.usuario_id = u.id 
                  AND um.tipo_pagina_id = " . mysqli_real_escape_string($link, $module_id) . "
              )";
}

$result = mysqli_query($link, $query);

if ($result === false) {
    echo json_encode(['error' => 'Error: ' . mysqli_error($link)]);
    exit;
}

$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

mysqli_free_result($result);
mysqli_close($link);

echo json_encode($users);