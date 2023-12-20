<?php
session_start();

// Limpia todas las variables de sesión
$_SESSION = array();

// Si se utiliza una cookie de sesión, borrarla
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruye la sesión
session_destroy();
header("Location: ../../login.html");
exit();
?>