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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- CSS de Bootstrap 4 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Estilos CSS de DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="../assets/css/styles_dark.css">

    <!-- JS de DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- JS de DataTables con soporte para Bootstrap 4 -->
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://kit.fontawesome.com/7011384382.js" crossorigin="anonymous"></script>

    <!-- usados para gráficos-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment"></script>
    <script src="../assets/js/jquery.redirect.js"></script>
</head>

<body>
    <header>

        <div class="header_estatico">

            <div class="logo-title-container">
                <button id="toggle-sidebar-btn" class="buttonreset togglesibar"><img src="../assets/images/menuburger.svg" alt="ocultar sidebar" width="24" height="24"></button>
                <img src="../assets/images/logo_reccius_medicina_especializada-1.png" id="logo" name="logo" alt="Logo" href="https://gestionipn.cl/reccius/pages/index.php" class="logo" />
            </div>

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="breadcrumb-container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="library.php">Library</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Data</li>
                </ol>
            </nav>

            <div id="notificaciones" name="notificaciones" class="notifications">
                <div class="notification_container">
                    <i class="fas fa-bell"></i>
                    <span id="contador_notificaciones" name="contador_notificaciones" class="notification-count">0</span> <!-- Contador inicializado en 0 -->
                </div>
            </div>

            <div class="user-info">
                <img src="../assets/images/perfil.png" alt="Foto de perfil" class="foto-perfil">
                <div class="dropdown">
                    <button class="dropbtn">
                        <span class="username">usuario</span><br>
                        <span class="user-role" style="font-style: italic;">(administrador)</span>
                    </button>
                    <div class="dropdown-content">
                        <a id="configuracion" href="modificar_perfil.php">Modificar Perfil</a>
                        <a href="./backend/login/logoutBE.php">Cerrar Sesión</a>
                    </div>
                </div>
                <img src="../assets/images/configuraciones.svg" alt="Foto de perfil" class="foto-config">
            </div>
        </div>

    </header>

    <div class="container_fas">

        <aside class="sidebar c-scroll">
            <ul id="sidebarList">
                <li class="title">Gestión de Usuarios</li>
                <li class="item" id="usuarios">
                    <a href="#usuarios" class="btn_lateral">
                        <spam>
                            <img src="../assets/images/usuario.svg" alt="Icono de usuario" class="icono-usuario" height="24" weight="24" /> <!-- Icono SVG agregado aquí -->
                        </spam> Usuarios y Roles
                    </a>
                    <div class="smenu">
                        <a id="crear-usuario" href="#" class="con-borde-inferior">
                            <span>
                                <img src="../assets/images/agregar_usuario.svg" alt="Icono de usuario" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG agregado aquí -->
                            </span>
                            Crear Usuario
                        </a>
                        <a id="asignar-roles" href="#">
                            <span>
                                <img src="../assets/images/asignarrol.svg" alt="Icono de usuario" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG agregado aquí -->
                            </span>
                            Asignar Roles
                        </a>
                    </div>
                </li>
                <li class="title">Calidad</li>

                <li class="item" id="Especificaciones">
                    <a href="#Especificaciones" class="btn_lateral">
                        <spam>
                            <img src="../assets/images/especificaciones.svg" alt="Icono de usuario" class="icono-usuario" height="24" weight="24" /> <!-- Icono SVG agregado aquí -->
                        </spam> Especificaciones
                    </a>
                    <div class="smenu">
                        <a id="especificacion_producto" href="#" class="con-borde-inferior">
                            <span>
                                <img src="../assets/images/crear_especificaciones.svg" alt="Icono de usuario" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG agregado aquí -->
                            </span>
                            Crear especificaciones de producto
                        </a>
                        <a id="listado_especificacion_producto" href="#">
                            <span>
                                <img src="../assets/images/listado.svg" alt="Icono de usuario" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG agregado aquí -->
                            </span>
                            Listado de especificaciones de producto
                        </a>
                    </div>

                </li>
                <li class="item" id="Solicitudes_de_Analisi">
                    <a href="#Solicitudes_de_Analisi" class="btn_lateral">
                        <span>
                            <img src="../assets/images/analisis.svg" alt="Icono de solicitudes" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG actualizado aquí -->
                        </span>
                        Solicitudes de Análisis
                    </a>
                    <div class="smenu">
                        <a id="listado_solicitudes_analisis" href="#" class="con-borde-inferior">
                            <span>
                                <img src="../assets/images/listado.svg" alt="Icono de listado de análisis" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG actualizado aquí -->
                            </span>
                            Listado de solicitudes de análisis
                        </a>
                        <a id="listado_acta_muestreo" href="#" class="con-borde-inferior">
                            <span>
                                <img src="../assets/images/listado.svg" alt="Listado Acta de Muestreo" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG actualizado aquí -->
                            </span>
                            Listado de Actas de Muestreo
                        </a>
                        <a id="preparacion_solicitud" href="#" class="con-borde-inferior">
                            <span>
                                <img src="../assets/images/preparacion.svg" alt="Icono de solicitud" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG actualizado aquí -->
                            </span>
                            Preparación solicitud
                        </a>
                        <a id="preparacion_analisis" href="#">
                            <span>
                                <img src="../assets/images/acta.svg" alt="Icono de solicitud" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG actualizado aquí -->
                            </span>
                            Preparar Acta de Muestreo
                        </a>
                        <!-- Nuevo apartado para el listado de productos disponibles -->
                        <a id="listado_productos_disponibles" href="#">
                            <span>
                                <img src="../assets/images/listado.svg" alt="Icono de productos disponibles" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG actualizado aquí -->
                            </span>
                            Listado de productos disponibles
                        </a>
                        <a id="documento_acta_muestreo" href="#">
                            <span>
                                <img src="../assets/images/acta.svg" alt="Icono de solicitud" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG actualizado aquí -->
                            </span>
                            DOCUMENTO ACTA DE MUESTREO
                        </a>

                        <a id="documento_Analisis_externo" href="#">
                            <span>
                                <img src="../assets/images/acta.svg" alt="Icono de solicitud" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG actualizado aquí -->
                            </span>
                            documento_Analisis_externo
                        </a>
                    </div>
                </li>

                <li class="item" id="Acta_Liberacion_o_Rechazo">
                    <a href="#Acta_Liberacion_o_Rechazo" class="btn_lateral">
                        <span>
                            <img src="../assets/images/liberacion.svg" alt="Icono de Acta Liberación o Rechazo" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG actualizado aquí -->
                        </span>
                        Acta Liberación o Rechazo
                    </a>
                    <div class="smenu">
                        <a id="acta_liberacion" href="#" class="con-borde-inferior">
                            <span>
                                <img src="../assets/images/liberacion.svg" alt="Icono de Acta Liberación" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG actualizado aquí -->
                            </span>
                            Acta liberación o rechazo
                        </a>
                        <a id="resultados_laboratorio" href="#">
                            <span>
                                <img src="../assets/images/resultados.svg" alt="Icono de Resultados Laboratorio" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG actualizado aquí -->
                            </span>
                            Ingreso resultados de laboratorio
                        </a>
                    </div>
                </li>
                <li class="title">Otros</li>
                <li class="item" id="calidad">
                    <a href="#calidad" class="btn_lateral"><i class="fa fa-envelope"></i> Calidad</a>
                    <div class="smenu">
                        <a id="acta_liberacion" href="#">Acta liberacion o rechazo</a>
                        <a id="resultados_laboratorio" href="#">Ingreso resultados de laboratorio</a>

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
                        <a id="testeo" href="">TEST DOCUMENTOS</a>
                        <a href="">Paso 3</a>
                        <a href="">Paso 4</a>
                    </div>
                </li>
                <li class="item" id="ordenes_de_compra">
                    <a href="#ordenes_de_compra" class="btn_lateral"><i class="fa fa-envelope"></i> Ordenes de Compra</a>
                    <div class="smenu">
                        <a href="">Ingreso</a>
                        <a id="testeo" href="">TEST DOCUMENTOS</a>
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
            <div class="text-center" id="loading-spinner" style="display: none;">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
            </div>
        </main>
    </div>
    <script src="../assets/js/scripts_index.js"></script>
</body>

</html>
<script>
    function fetchUserInfo() {
        fetch('./backend/usuario/obtener_usuarioBE.php')
            .then(response => response.json())
            .then(data => {
                if (data.usuario) {
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


    obtenNotificaciones();
    fetchUserInfo();
</script>
<script>
    document.getElementById('toggle-sidebar-btn').addEventListener('click', function() {
        var sidebar = document.querySelector('.sidebar');
        var content = document.querySelector('.content');
        sidebar.classList.toggle('sidebar-hidden');
        content.classList.toggle('content-expanded');
    });
</script>