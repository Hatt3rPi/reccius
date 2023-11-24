<?php
require_once "/home/customw2/conexiones/config_reccius.php";

function getRoles() {
    global $link;
    $stmt = mysqli_prepare($link, "SELECT id, nombre FROM roles");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $roles = [];
    while($row = mysqli_fetch_assoc($result)) {
        $roles[] = $row;
    }
    return $roles;
}

?>
