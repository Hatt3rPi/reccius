<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

 if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
 echo json_encode([]);
 exit;
 }

$texto_a_buscar = isset($_GET['texto']) ? mysqli_real_escape_string($link, $_GET['texto']) : '';

$queryDinamica = "SELECT id, materia_prima, precio_por_kg_lt, factor_reccius, disponibilidad FROM recetariomagistral_tarifas_materiasprimas WHERE materia_prima LIKE '%$texto_a_buscar%'";

$result = mysqli_query($link, $queryDinamica);

$productos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $productos[] = ['id' => $row['id'], 'nombre' => $row['materia_prima']];
}

echo json_encode($productos);
?>