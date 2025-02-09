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
    $id_page = $_GET['id_page'] ?? null; //id de la pagina
    if ($id_page !== null) {
        $relationships = $model->getUserPageRelationships($id_page);
        echo json_encode($relationships);
        exit;
    }

    $pages = $model->getPages();
    echo json_encode($pages);
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuarios = $_POST['usuarios'] ?? null;//array de usuarios
    $pagina_id = $_POST['pagina_id'] ?? null; //pagina_id 
    if( $usuarios == null || $pagina_id == null ){
        echo json_encode(['error' => 'Faltan datos']);
        exit;
    }

}


?>