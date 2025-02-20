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
        $userId = $_POST['userId'] ?? null;
        $moduleId = $_POST['moduleId'] ?? null;
        
        error_log("Intentando añadir usuario. UserId: $userId, ModuleId: $moduleId");
        
        if($userId === null || $moduleId === null){
            throw new Exception('Parámetros requeridos: userId, moduleId');
        }
        
        $result = $model->addUserToModuleRelationship($userId, $moduleId);
        error_log("Resultado de addUserToModuleRelationship: " . json_encode($result));
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        error_log("Error en addUserToModuleRel: " . $e->getMessage());
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fn = $_POST['fn'] ?? null;
    error_log("Función: '$fn' ? 'addUserToModuleRelationship'");
    if($fn === 'addUserToModuleRelationship' ){
        error_log('In -> addUserToModuleRelationship');
        addUserToModuleRel();
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
