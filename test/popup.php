<?php
session_start();

// Verificar si la variable de sesión "usuario" no está establecida o está vacía.
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    // Redirigir al usuario a la página de inicio de sesión.
    header("Location: login.html");
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<div class= "popup">
    <article class= "popup__card">
        <header class= "popup__header">
        titulo
        <button> cerrar</button>
        </header>
        <main>
            <button>
                ok                
            </button>
        </main>
    </article>
</div>

</html>