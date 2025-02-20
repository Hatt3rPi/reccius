<?php
// archivo: pages/backend/paginas/pagesBe.php
session_start();

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: https://customware.cl/reccius/pages/login.html");
    exit;
}

require_once "/home/customw2/conexiones/config_reccius.php";
require_once "pagina_model.php";

// Verificar conexión explícitamente
if (!isset($link)) {
    die(json_encode(['error' => 'No se pudo establecer la conexión a la base de datos']));
}

if (!($link instanceof mysqli)) {
    die(json_encode(['error' => 'La conexión no es válida']));
}

try {
    $model = new PaginaModel($link);
} catch (Exception $e) {
    die(json_encode(['error' => $e->getMessage()]));
}

header('Content-Type: application/json; charset=utf-8');

function addUserToModuleRel(){
    global $model;
    try {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);
        if (!$data) {
            throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
        }
        
        $userId = $data['userId'] ?? null;
        $moduleId = $data['moduleId'] ?? null;
        
        
        if($userId === null || $moduleId === null){
            throw new Exception('Parámetros requeridos: userId, moduleId');
        }
        
        $model->addUserToModuleRelationship($userId, $moduleId);
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
function changeUserRole(){
    global $model;
    try {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);
        if (!$data) {
            throw new Exception('Error al decodificar JSON: ' . json_last_error_msg());
        }
        
        $userId = $data['userId'] ?? null;
        $moduleId = $data['moduleId'] ?? null;
        $rolId = $data['rolId'] ?? null;

        
        if($userId === null || $moduleId === null || $rolId === null){
            throw new Exception('Parámetros requeridos: userId, moduleId, rolId');
        }
        
        $model->changeUserRole($userId, $moduleId, $rolId);
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    $fn = $data['fn'] ?? null;
    
    if($fn === 'addUserToModuleRelationship'){
        addUserToModuleRel();
    }
    if($fn === 'changeUserRole'){
        changeUserRole();
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
