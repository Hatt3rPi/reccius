<?php
// pages\backend\login\loginBE.php
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
    header("Location: ../../index.php");
    exit();
} else {
    $csrfToken = generateCSRFToken();
}

if (isset($_POST['login'])) {
    $loginInput = escape($_POST['username']);
    $password = escape($_POST['password']);
    
    // Modificación aquí: buscar por usuario o correo
    $query="SELECT a.id, a.usuario, a.contrasena,a.ruta_registroPrestadoresSalud, b.nombre as rol, a.nombre, a.correo, a.foto_perfil, a.foto_firma, a.cargo FROM usuarios as a LEFT JOIN roles as b ON a.rol_id=b.id WHERE a.usuario = ? OR a.correo = ?";
    $variables = [$loginInput, $loginInput]; 
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "ss", $loginInput, $loginInput);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($result);
    $resultadoArray = mysqli_fetch_assoc($result);
    
    $user = $usuario['usuario'] ? $usuario['usuario'] : null;
    $resultado = $resultadoArray ? 1 : 0; // Suponiendo que 1 es éxito y 0 es fracaso
    $error = mysqli_stmt_error($stmt) ? "Error al ejecutar la consulta: " . mysqli_stmt_error($stmt) : null;
    registrarTrazabilidad($user, $_SERVER['PHP_SELF'], 'login', 'usuarios', null, $query, $variables, $resultado, $error);


    if ($usuario && password_verify($password, $usuario['contrasena'])) {
        $csrfToken = generateCSRFToken();
        $_SESSION['id_usuario'] = escape($usuario['id']);
        $_SESSION['usuario'] = escape($usuario['usuario']);
        $_SESSION['rol'] = escape($usuario['rol']);
        $_SESSION['nombre'] = escape($usuario['nombre']);
        $_SESSION['correo'] = escape($usuario['correo']);
        $_SESSION['certificado'] = escape($usuario['ruta_registroPrestadoresSalud']);
        $_SESSION['csrf_token'] = $csrfToken;
        $_SESSION['foto_perfil'] = escape($usuario['foto_perfil']);
        $_SESSION['foto_firma'] = escape($usuario['foto_firma']);
        $_SESSION['cargo'] = escape($usuario['cargo']);
        
        header("Location: ../../index.php");
        exit();
    } else {
        header("Location: ../../login.html?error=invalid_credentials");
        exit();
    }
}
?>
