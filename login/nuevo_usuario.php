<?php
// Incluir el archivo de conexión a la base de datos
require_once "/home/gestio10/procedimientos_almacenados/config_reccius.php";

// Verificar si el formulario ha sido enviado
if (isset($_POST['submit'])) {
    // Obtener el nombre de usuario y las contraseñas ingresadas y filtrarlos
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $password = mysqli_real_escape_string($link, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($link, $_POST['confirm_password']);

    // Verificar si el nombre de usuario ya existe en la base de datos
    $stmt = mysqli_prepare($link, "SELECT * FROM usuarios WHERE usuario = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($result);

    if ($usuario) {
        // El nombre de usuario ya existe en la base de datos, mostrar un mensaje de error
        $error = "El nombre de usuario ya está en uso.";
    } elseif ($password !== $confirm_password) {
        // Las contraseñas no coinciden, mostrar un mensaje de error
        $error = "Las contraseñas no coinciden.";
    } else {
        // Hashear la contraseña utilizando password_hash()
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Consulta preparada para insertar el nuevo usuario en la base de datos
        $stmt = mysqli_prepare($link, "INSERT INTO usuarios (usuario, contrasena) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);
        mysqli_stmt_execute($stmt);

        // Redirigir al usuario a la página de inicio de sesión
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nuevo usuario</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <form method="POST">
            <h1>Nuevo usuario</h1>
            <?php if (isset($error)) { ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required pattern=".{6,}" title="La contraseña debe tener al menos 6 caracteres">
            <label for="confirm_password">Confirmar contraseña</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <input type="submit" name="submit" value="Crear cuenta">
        </form>
    </div>
</body>
</html>