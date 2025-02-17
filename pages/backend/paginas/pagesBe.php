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

function cambioPorPagina($usuarios, $pagina_id, $model)
{
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

function cambioPorPaginaTipo($usuarios, $pagina_tipo_id, $model)
{
    $success = true;
    $messages = [];
    $resultados = [];

    foreach ($usuarios as $usuario) {
        if ($usuario['checked']) {
            // Crear relaciones para todas las páginas del tipo si está marcado
            $result = $model->createUserTipoPage($usuario['id_usuario'], $pagina_tipo_id);
            if (!$result['success']) {
                $success = false;
                $messages[] = "Error al procesar usuario {$usuario['id_usuario']}: " . implode(', ', $result['errores']);
            } else {
                $resultados[] = [
                    'usuario_id' => $usuario['id_usuario'],
                    'paginas_procesadas' => $result['paginas_procesadas']
                ];
            }
        } else {
            // Obtener todas las páginas del tipo y eliminar accesos
            $result = $model->deleteUserTipoPage($usuario['id_usuario'], $pagina_tipo_id);
            if (!$result['success']) {
                $success = false;
                $messages[] = "Error al eliminar accesos del usuario {$usuario['id_usuario']}: " . implode(', ', $result['errores']);
            }
        }
    }

    echo json_encode([
        'success' => $success,
        'messages' => $messages,
        'resultados' => $resultados
    ]);
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode JSON input
    $data = json_decode(file_get_contents('php://input'), true);
    $usuarios = $data['usuarios'] ?? null;
    $pagina_id = $data['pagina_id'] ?? null;
    $pagina_tipo_id = $data['pagina_tipo_id'] ?? null;

    if ($usuarios === null) {
        echo json_encode(['error' => 'Faltan datos de usuarios']);
        exit;
    }

    if ($pagina_id !== null) {
        cambioPorPagina($usuarios, $pagina_id, $model);
    } else if ($pagina_tipo_id !== null) {
        cambioPorPaginaTipo($usuarios, $pagina_tipo_id, $model);
    } else {
        echo json_encode(['error' => 'Falta especificar pagina_id o pagina_tipo_id']);
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id_pagina = $_GET['id_pagina'] ?? null; //id de la pagina
    if ($id_pagina !== null) {
        $relationships = $model->getUserPageRelationships($id_pagina);
        echo json_encode($relationships);
        exit;
    }

    $pages = $model->getPages();
    $modules = $model->getModules();
    $pageRoles = $model->getRolPages();
    echo json_encode(
        [
            'pages' => $pages,
            'modules' => $modules,
            'pageRoles' => $pageRoles
        ]
    );
    exit;
}
