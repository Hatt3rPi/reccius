<?php
// archivo: pages/backend/paginas/pagesBe.php
require_once "/home/customw2/conexiones/config_reccius.php";
session_start();

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}

require_once "pagina_model.php";

// Asegurarse de que $link existe y es válido
if (!isset($link) || !($link instanceof mysqli)) {
    die(json_encode(['error' => 'Error de conexión a la base de datos']));
}

try {
    $model = new PaginaModel($link);
} catch (Exception $e) {
    die(json_encode(['error' => $e->getMessage()]));
}

header('Content-Type: application/json; charset=utf-8');

function addUserToModuleRelationship(){
    global $model;

    $userId = $_POST['userId'] ?? null;
    $moduleId = $_POST['moduleId'] ?? null;
    if($userId === null || $moduleId === null){
        echo json_encode(['error' => 'Parámetros requeridos: userId, moduleId']);
        exit;
    }
    $model->addUserToModuleRelationship($userId, $moduleId);
    echo json_encode(['success' => true]);
    exit;

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fn = $_POST['fn'] ?? null;
    if($fn === 'addUserToModuleRelationship'){
        addUserToModuleRelationship();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $modulo_id = $_GET['module_id'] ?? null;
    if ($modulo_id !== null) {
        echo json_encode($model->getModuleRelationships($modulo_id));
        exit;
    }

    echo json_encode([
        'modules' => $model->getModules(),
        'pageRoles' => $model->getRolPages(),
    ]);
    exit;
}
