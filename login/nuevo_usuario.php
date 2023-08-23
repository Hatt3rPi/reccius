<?php
// Incluir el archivo de conexión a la base de datos
require_once "/home/gestio10/procedimientos_almacenados/config_ayun.php";

// Verificar si se ha establecido la conexión a la base de datos
try {
    // Attempt to connect to MySQL database using PDO
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    // If connection fails, show error message and stop script
    die("ERROR: No se pudo conectar a la base de datos. " . $e->getMessage());
}

// Verificar si el formulario ha sido enviado
if (isset($_POST['submit'])) {
    // Obtener el nombre de usuario y las contraseñas ingresadas y filtrarlos
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $confirm_password = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_STRING);

    // Verificar si el nombre de usuario ya existe en la base de datos
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
    $stmt->execute(array('usuario' => $username));
    $usuario = $stmt->fetch();

    if ($usuario) {
        // El nombre de usuario ya existe en la base de datos, mostrar un mensaje de error
        $error = "El nombre de usuario ya está en uso.";
    } else if ($password !== $confirm_password) {
        // Las contraseñas no coinciden, mostrar un mensaje de error
        $error = "Las contraseñas no coinciden.";
    } else {
        // Hashear la contraseña utilizando password_hash()
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Consulta preparada para insertar el nuevo usuario en la base de datos
        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, contrasena) VALUES (:usuario, :contrasena)");
        $stmt->execute(array('usuario' => $username, 'contrasena' => $hashed_password));

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
    <link rel="stylesheet" href="a/css/style.css">
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