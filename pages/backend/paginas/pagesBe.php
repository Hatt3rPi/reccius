<?php
// archivo: pages/backend/paginas/pagesBe.php
require_once "/home/customw2/conexiones/config_reccius.php";
session_start();
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}
require_once "pagina_model.php";

$model = new PaginaModel($pdo);

header('Content-Type: application/json; charset=utf-8');

function cambioPorPagina($usuarios, $pagina_id, $model)
{
    $success = true;
    $messages = [];

    foreach ($usuarios as $usuario) {
        if ($usuario['checked']) {
            if (!$model->asignarUsuarioAPagina($usuario['id_usuario'], $pagina_id)) {
                $success = false;
                $messages[] = "Error al asignar usuario {$usuario['id_usuario']} a la página";
            }
        } else {
            if (!$model->eliminarUsuarioDePagina($usuario['id_usuario'], $pagina_id)) {
                $success = false;
                $messages[] = "Error al eliminar usuario {$usuario['id_usuario']} de la página";
            }
        }
    }

    echo json_encode([ 'success' => $success, 'messages' => $messages ]);
    exit;
}

function cambioPorPaginaTipo($usuarios, $pagina_tipo_id, $model)
{
    $success = true;
    $messages = [];
    $resultados = [];

    foreach ($usuarios as $usuario) {
        if ($usuario['checked']) {
            $result = $model->asignarUsuarioATipoPagina($usuario['id_usuario'], $pagina_tipo_id);
            if (!$result['success']) {
                $success = false;
                $messages[] = "Error en usuario {$usuario['id_usuario']}: " . implode(', ', $result['errores']);
            } else {
                $resultados[] = [
                    'usuario_id' => $usuario['id_usuario'],
                    'paginas_procesadas' => $result['paginas_procesadas']
                ];
            }
        } else {
            $result = $model->eliminarUsuarioDeTipoPagina($usuario['id_usuario'], $pagina_tipo_id);
            if (!$result['success']) {
                $success = false;
                $messages[] = "Error al eliminar accesos de usuario {$usuario['id_usuario']}: " . implode(', ', $result['errores']);
            }
        }
    }

    echo json_encode([ 'success' => $success, 'messages' => $messages, 'resultados' => $resultados ]);
    exit;
}

function cambioPorModulo($usuarios, $modulo_id, $model)
{
    $success = true;
    $messages = [];

    foreach ($usuarios as $usuario) {
        if ($usuario['checked']) {
            if (!$model->asignarUsuarioAModulo($usuario['id_usuario'], $modulo_id)) {
                $success = false;
                $messages[] = "Error al asignar usuario {$usuario['id_usuario']} al módulo";
            }
        } else {
            if (!$model->eliminarUsuarioDeModulo($usuario['id_usuario'], $modulo_id)) {
                $success = false;
                $messages[] = "Error al eliminar usuario {$usuario['id_usuario']} del módulo";
            }
        }
    }

    echo json_encode([ 'success' => $success, 'messages' => $messages ]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $usuarios = $data['usuarios'] ?? null;
    $pagina_id = $data['pagina_id'] ?? null;
    $pagina_tipo_id = $data['pagina_tipo_id'] ?? null;
    $modulo_id = $data['modulo_id'] ?? null;

    if ($usuarios === null) {
        echo json_encode(['error' => 'Faltan datos de usuarios']);
        exit;
    }

    if ($pagina_id !== null) {
        cambioPorPagina($usuarios, $pagina_id, $model);
    } else if ($pagina_tipo_id !== null) {
        cambioPorPaginaTipo($usuarios, $pagina_tipo_id, $model);
    } else if ($modulo_id !== null) {
        cambioPorModulo($usuarios, $modulo_id, $model);
    } else {
        echo json_encode(['error' => 'Falta especificar pagina_id, pagina_tipo_id o modulo_id']);
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id_pagina = $_GET['id_pagina'] ?? null;
    if ($id_pagina !== null) {
        echo json_encode($model->obtenerRelacionesUsuariosPagina($id_pagina));
        exit;
    }

    $modulo_id = $_GET['modulo_id'] ?? null;
    if ($modulo_id !== null) {
        echo json_encode($model->obtenerRelacionesUsuariosModulo($modulo_id));
        exit;
    }

    echo json_encode([
        'pages' => $model->obtenerPaginas(),
        'modules' => $model->obtenerModulos(),
        'pageRoles' => $model->obtenerRolesPaginas()
    ]);
    exit;
}
