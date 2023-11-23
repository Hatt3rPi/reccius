<?php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

function escape($value) {
    global $link;
    return mysqli_real_escape_string($link, $value);
}

function generateCSRFToken() {
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

if (isset($_SESSION['usuario'])) {
    header("Location: ../reccius/pages/index.html");
    exit();
} else {
    $csrfToken = generateCSRFToken();
}

if (isset($_POST['login'])) {
    $username = escape($_POST['username']);
    $password = escape($_POST['password']);
    
    $stmt = mysqli_prepare($link, "SELECT a.usuario, a.contrasena, b.nombre as rol, a.nombre, a.correo FROM usuarios as a left join roles as b on a.rol_id=b.id WHERE a.usuario = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($result);

    if ($usuario && password_verify($password, $usuario['contrasena'])) {
        $csrfToken = generateCSRFToken();
        $_SESSION['usuario'] = escape($usuario['usuario']);
        $_SESSION['rol'] = escape($usuario['rol']);
        $_SESSION['nombre'] = escape($usuario['nombre']);
        $_SESSION['correo'] = escape($usuario['correo']);
        $_SESSION['csrf_token'] = $csrfToken;

        header("Location: ../reccius/pages/index.html");
        exit();
    } else {
        header("Location: ../reccius/pages/login.html?error=invalid_credentials");
        exit();
    }
}
?>
