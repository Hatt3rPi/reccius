<?php
// archivo: pages/backend/paginas/pagesBe.php
require_once "/home/customw2/conexiones/config_reccius.php";
session_start();
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}
require_once "pagina_model.php";

$model = new PaginaModel($link);
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id_pagina = $_GET['id_pagina'] ?? null; //id de la pagina
    if ($id_pagina !== null) {
        $relationships = $model->getUserPageRelationships($id_pagina);
        echo json_encode($relationships);
        exit;
    }

    $pages = $model->getPages();
    echo json_encode($pages);
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode JSON input
    $data = json_decode(file_get_contents('php://input'), true);
    $usuarios = $data['usuarios'] ?? null;
    $pagina_id = $data['pagina_id'] ?? null;
    
    if ($usuarios === null || $pagina_id === null) {
        echo json_encode(['error' => 'Faltan datos']);
        exit;
    }

    $success = true;
    $messages = [];

    foreach ($usuarios as $usuario) {
        if ($usuario['checked']) {
            // Crear relación si está marcado
            if (!$model->createUserPage($usuario['id_usuario'], $pagina_id)) {
                $success = false;
                $messages[] = "Error al crear relación para usuario {$usuario['id_usuario']}";
            }
        } else {
            // Eliminar relación si no está marcado
            if (!$model->deleteUserPage($usuario['id_usuario'], $pagina_id)) {
                $success = false;
                $messages[] = "Error al eliminar relación para usuario {$usuario['id_usuario']}";
            }
        }
    }

    echo json_encode([
        'success' => $success,
        'messages' => $messages
    ]);
    exit;
}

?>