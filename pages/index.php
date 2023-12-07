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
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reccius</title>
        <script  src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
        <link rel="stylesheet" href="../assets/css/styles_dark.css">
    
        <!-- CSS de Bootstrap 4 -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
        <!-- Estilos CSS de DataTables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    
        
    
        <!-- JS de DataTables -->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    
        <!-- JS de DataTables con soporte para Bootstrap 4 -->
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://kit.fontawesome.com/7011384382.js" crossorigin="anonymous"></script>

        <!-- usados para gráficos-->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment"></script>
    </head>
<body>
    <header>
        <div class="header_estatico">
            <div class="logo-title-container">
                <img src="../assets/images/logo_reccius_medicina_especializada-1.png" alt="Logo" href="https://gestionipn.cl/reccius/pages/index.php" class="logo"/>
            </div>
            <div class="user-info">
                <img src="../assets/images/perfil.png" alt="Foto de perfil" class="foto-perfil">
                <div class="dropdown">
                    <button class="dropbtn">
                        <span class="username" >usuario</span><br> 
                        <span class="user-role" style="font-style: italic;">(administrador)</span>
                    </button>
                    <div class="dropdown-content">

                        <a id="configuracion" href="modificar_perfil.php">Modificar Perfil</a>
                        <a href="./backend/login/logoutBE.php">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container_fas">
        <aside class="sidebar">
            <ul id="sidebarList">
                <li><a class="" style="pointer-events: none;"> </a></li>
                <li class="item" id="usuarios">
                    <a href="#usuarios" class="btn_lateral"><i class="fa fa-user"></i> Usuarios y Roles</a>
                    <div class="smenu">
                        <a id="crear-usuario" href="#">Crear Usuario</a>
                        <a id="asignar-roles" href="#">Asignar Roles</a>
                    </div>
                </li>
                <li class="item" id="calidad">
                    <a href="#calidad" class="btn_lateral"><i class="fa fa-envelope"></i> Calidad</a>
                    <div class="smenu">
                        <a id="especificacion_producto" href="#">Crear especificaciones de producto</a>
                        <a id="preparacion_solicitud" href="#">Solicitar analisis 1</a>
                        <a id="preparacion_analisis" href="#">Solicitar analisis 2</a>
                        <a id="acta_liberacion" href="#">Acta liberacion o rechazo</a> 

                    </div>
                </li>
                <li class="item" id="materias_primas">
                    <a href="#Materias_primas" class="btn_lateral"><i class="fa fa-envelope"></i> Materias Primas</a>
                    <div class="smenu">
                        <a href="">Listado materias primas disponibles</a>
                        <a href="">Paso 2</a>
                        <a href="">Paso 3</a>
                        <a href="">Paso 4</a>
                    </div>
                </li>
                <li class="item" id="recetas_magistrales">
                    <a href="#recetas_magistrales" class="btn_lateral"><i class="fa fa-envelope"></i> Recetas Magistrales</a>
                    <div class="smenu">
                        <a href="">Ingreso</a>
                        <a href="">Paso 2</a>
                        <a href="">Paso 3</a>
                        <a href="">Paso 4</a>
                    </div>
                </li>
            </ul>
        </aside>
        <main class="content">
            <div id="dynamic-content">
                <!-- El contenido se cargará dinámicamente aquí -->
            </div>
        </main>
    </div>
    <script  src="../assets/js/scripts_index.js"></script>
</body>
</html>
<script>
function fetchUserInfo() {
    fetch('./backend/usuario/obtener_usuarioBE.php')
        .then(response => response.json())
        .then(data => {
            if(data.usuario) {
                document.querySelector('.username').textContent = data.nombre;
                document.querySelector('.user-role').textContent = '(' + data.rol + ')';
                // Actualiza la imagen de perfil
                const fotoPerfil = document.querySelector('.foto-perfil');
                if (data.foto_perfil && data.foto_perfil.trim() !== "") {
                    // Construye la ruta completa a la imagen de perfil
                    fotoPerfil.src = "../assets/uploads/perfiles/" + data.foto_perfil;
                } else {
                    // Usa la imagen genérica si no hay foto de perfil
                    fotoPerfil.src = "../assets/images/perfil.png";
                }
            }
        })
        .catch(error => console.error('Error:', error));
}



    fetchUserInfo();
</script>