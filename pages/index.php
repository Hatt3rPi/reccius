<?php
// archivo: pages\index.php
session_start();

// Verificar si la variable de sesión "usuario" no está establecida o está vacía.
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    // Redirigir al usuario a la página de inicio de sesión.
    header("Location: login.html");
    exit;
}
$firma_no_ingresada = false;
if (!isset($_SESSION['foto_firma']) || empty($_SESSION['foto_firma'])) {
    $firma_no_ingresada = true;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reccius</title>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="../assets/js/notify.js"></script>

    <!-- Estilos CSS de DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="../assets/css/styles_dark.css">

    <!-- Bootstrap Datepicker CSS / JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js"></script>

    <!-- JS Moment Fechas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- JS de DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/7011384382.js" crossorigin="anonymous"></script>

    <!-- usados para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment"></script>

    <script src="../assets/js/jquery.redirect.js"></script>
    <link rel="icon" type="image/x-icon" href="../assets/images/icons8-r-30.png">
    <link rel="stylesheet" href="../assets/css/Notificacion.css">

    <!-- jsPDF y html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>

    <!-- Calendario -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/locales-all.min.js"></script>

    <!-- CKEditor -->

    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.1/ckeditor5.css" />
    <script type="importmap">
        {
            "imports": {
                "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.1/ckeditor5.js",
                "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.1/"
            }
        }
    </script>

    <style>
        .popover {
            border: 2px solid red;
            top: -70px !important;

        }

        .bs-popover-auto[x-placement^=bottom]>.arrow::after,
        .bs-popover-bottom>.arrow::after {
            border-bottom-color: #dc3545 !important;
            /* border: 2px solid red  !important; */
        }

        .popover-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .popover-header .close {
            cursor: pointer;
        }
    </style>
</head>

<body class="position-relative">
    <header>

        <div class="header_estatico">

            <div class="logo-title-container">
                <button id="toggle-sidebar-btn" class="buttonreset togglesibar"><img src="../assets/images/menuburger.svg" alt="ocultar sidebar" width="24" height="24"></button>
                <img src="../assets/images/logo_reccius_medicina_especializada-1.png" id="logo" name="logo" alt="Logo" href="https://customware.cl/reccius/pages/index.php" data-breadcrumb="Home > Data > Trazabilidad" class="logo" />
            </div>



            <div id="notificaciones" name="notificaciones" class="notifications" data-breadcrumb="Home > Notificaciones > Listado de Tareas">
                <div class="notification_container">
                    <i class="fas fa-bell"></i>
                    <span id="contador_notificaciones" name="contador_notificaciones" class="notification-count">0</span> <!-- Contador inicializado en 0 -->
                </div>
            </div>

            <div class="user-info">
                <img src="../assets/images/perfil.png" alt="Foto de perfil" class="foto-perfil">
                <div class="dropdown" <?php echo $firma_no_ingresada ? 'data-toggle="popover" data-html="true" title="Firma no ingresada <span class=\'close\'>&times;</span>" data-content="Favor adjuntar firma para poder firmar sus documentos cuando corresponda." data-trigger="manual"' : ''; ?>>

                    <button class="dropbtn">
                        <span class="username">usuario</span><br>
                        <span class="user-role" style="font-style: italic;">(administrador)</span>
                    </button>
                    <div class="dropdown-content">
                        <a id="configuracion" href="modificar_perfil.php" data-breadcrumb="Home > Configuraciones > Modificar perfil">Modificar Perfil</a>
                        <a href="./backend/login/logoutBE.php">Cerrar Sesión</a>
                    </div>
                </div>
                <img src="../assets/images/configuraciones.svg" alt="Foto de perfil" class="foto-config">
            </div>
        </div>

    </header>
    <!-- Breadcrumb que actuará como barra de navegación -->
    <nav aria-label="breadcrumb" class="breadcrumb-container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Primer Nodo</li>
            <li class="breadcrumb-item">Segundo Nodo</li>
        </ol>

    </nav>
    <div class="container_fas">
        <aside class="sidebar c-scroll">
            <ul id="sidebarList">
                <a id="home" href="#" data-breadcrumb="Home" class="">
                    <span>
                        <img src="../assets/images/agregar_usuario.svg" alt="Icono de usuario" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG agregado aquí -->
                    </span>
                    Home
                </a>
                <li class="title">Gestión de Usuarios</li>
                <li class="item" id="usuarios">
                    <a href="#usuarios" class="btn_lateral breadcrumb-btn_lateral">
                        <spam>
                            <img src="../assets/images/usuario.svg" alt="Icono de usuario" class="icono-usuario" height="24" weight="24" /> <!-- Icono SVG agregado aquí -->
                        </spam> Usuarios y Roles
                    </a>
                    <div class="smenu">
                        <a id="crear-usuario" href="#" data-breadcrumb="Home > Usuarios y Roles > Crear Usuario" class="con-borde-inferior">

                            <span>
                                <img src="../assets/images/agregar_usuario.svg" alt="Icono de usuario" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG agregado aquí -->
                            </span>
                            Crear Usuario
                        </a>
                        <a id="asignar-roles" href="#" data-breadcrumb="Home > Usuarios y Roles > Asignar Roles">
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
                        <a id="especificacion_producto" href="#" data-breadcrumb="Home > Especificaciones > Crear especificaciones de producto" class="con-borde-inferior">
                            <span>
                                <img src="../assets/images/crear_especificaciones.svg" alt="Icono de usuario" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG agregado aquí -->
                            </span>
                            Crear especificaciones de producto
                        </a>
                        <a id="listado_especificacion_producto" href="#" data-breadcrumb="Home > Especificaciones > Listado de especificaciones de producto">
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

                        <a id="listado_solicitudes_analisis" href="#" data-breadcrumb="Home > Solicitudes de Análisis > Listado de solicitudes de análisis" class="con-borde-inferior">
                            <span>
                                <img src="../assets/images/listado.svg" alt="Icono de listado de análisis" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG actualizado aquí -->
                            </span>
                            Listado de solicitudes de análisis
                        </a>
                        <a id="listado_acta_muestreo" href="#" data-breadcrumb="Home > Solicitudes de Análisis > Listado de Actas de Muestreo" class="con-borde-inferior">
                            <span>
                                <img src="../assets/images/listado.svg" alt="Listado Acta de Muestreo" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG actualizado aquí -->
                            </span>
                            Listado de Actas de Muestreo
                        </a>

                    </div>
                </li>
                <a id="listado_productos_disponibles" href="#" data-breadcrumb="Home > Calidad > Listado de productos disponibles">
                    <span>
                        <img src="../assets/images/listado.svg" alt="Icono de productos disponibles" class="icono-usuario" height="24" width="24" /> <!-- Icono SVG actualizado aquí -->
                    </span>
                    Productos en cuarentena y liberados
                </a>

                <li class="title">Recetario magistral</li>

                <li class="item" id="cotizador">
                    <a href="#Cotizador" class="btn_lateral">
                        <span>
                            <img src="../assets/images/calculator.svg" alt="Icono de Acta Liberación o Rechazo" class="icono-usuario" height="24" width="24" />
                        </span>
                        Cotizador
                    </a>
                    <div class="smenu">
                        <a id="cotizador_ingreso" href="#" data-breadcrumb="Home > Cotizador > Ingreso" class="con-borde-inferior">
                            <span>
                                <img src="../assets/images/liberacion.svg" alt="Icono de ingresar dentro de cotizador" class="icono-usuario" height="24" width="24" />
                            </span>
                            Ingreso
                        </a>
                        <a id="cotizador_busqueda" href="#" data-breadcrumb="Home > Cotizador > Buscar" class="con-borde-inferior">
                            <span>
                                <img src="../assets/images/search.svg" alt="Icono de buscar dentro de cotizador" class="icono-usuario" height="24" width="24" />
                            </span>
                            Buscar
                        </a>
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
    <script src="../assets/js/features_customware.js"></script>
    <script src="../assets/js/scripts_index.js?<?php echo time(); ?>"></script>
    <script src="../assets/js/botones.js"></script>
</body>

</html>
<script>
    $(document).ready(function() {
        $('#dynamic-content').load('index_administrador.php');
        $('[data-toggle="popover"]').popover({
            placement: 'bottom',
            trigger: 'manual' // Popover se mostrará manualmente
        });

        // Si la firma no está ingresada, mostrar el popover automáticamente
        <?php if ($firma_no_ingresada) : ?>
            $('[data-toggle="popover"]').popover('show');
            $('[data-toggle="popover"]').on('shown.bs.popover', function() {
                var popover = $(this).next('.popover');
            });
        <?php endif; ?>

        // Cerrar el popover al hacer clic en el botón con clase dropbtn
        $('.dropbtn').on('click', function() {
            $('[data-toggle="popover"]').popover('hide');
        });

        // Cerrar el popover al hacer clic fuera del popover
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.popover').length && !$(e.target).closest('.dropbtn').length) {
                $('[data-toggle="popover"]').popover('hide');
            }
        });

        // Cerrar el popover al hacer clic en el botón de cierre dentro del popover
        $(document).on('click', '.popover .close', function() {
            $('[data-toggle="popover"]').popover('hide');
        });
    });

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
                        fotoPerfil.src = data.foto_perfil;
                    } else {
                        // Usa la imagen genérica si no hay foto de perfil
                        fotoPerfil.src = "../assets/images/perfil.png";
                    }
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function reloadPageBypassCacheHash() {
        const PageVersion = window.localStorage.getItem("PageVersion")
        if (PageVersion == null) {
            window.localStorage.setItem("PageVersion", AppConfig.VERSION);
            return;
        }
        if (PageVersion !== AppConfig.VERSION) {
            window.location.hash = "nocache=" + PageVersion;
            window.location.reload(true);
            return;
        }
    }

    obtenNotificaciones();
    fetchUserInfo();

    document.getElementById('toggle-sidebar-btn').addEventListener('click', function() {
        var sidebar = document.querySelector('.sidebar');
        var content = document.querySelector('.content');
        var nav = document.querySelector('.breadcrumb-container');

        nav.classList.toggle('breadcrumbexpanded');
        sidebar.classList.toggle('sidebar-hidden');
        content.classList.toggle('content-expanded');

    });

    document.addEventListener("DOMContentLoaded", function() {
        // Inicializa el breadcrumb al cargar la página
        inicializarBreadcrumb();

        const breadcrumbLinks = document.querySelectorAll("[data-breadcrumb]");

        function inicializarBreadcrumb() {
            // Solo muestra "Home" al cargar la página
            const breadcrumb = document.querySelector(".breadcrumb");
            breadcrumb.innerHTML = ''; // Limpia el breadcrumb actual
            const liHome = document.createElement("li");
            liHome.className = "breadcrumb-item";
            liHome.textContent = "Home"; // Cambiado para ser solo texto
            breadcrumb.appendChild(liHome);
        }

        function updateBreadcrumb(path) {
            // Actualiza el breadcrumb según el enlace clickeado
            const breadcrumb = document.querySelector(".breadcrumb");
            breadcrumb.innerHTML = ''; // Limpia el breadcrumb actual

            const paths = path.split(" > ");
            paths.forEach((p, index) => {
                const li = document.createElement("li");
                li.className = "breadcrumb-item";
                li.textContent = p; // Cambiado para ser solo texto
                breadcrumb.appendChild(li);
            });
        }

        // Asigna el listener a cada enlace que afecte el breadcrumb
        breadcrumbLinks.forEach(link => {
            link.addEventListener("click", function(e) {
                e.preventDefault(); // Previene la acción por defecto
                const path = this.getAttribute("data-breadcrumb");
                updateBreadcrumb(path);
            });
        });

        var goTo = "<?php echo isset($_SESSION['go_to']) ? $_SESSION['go_to'] : ''; ?>"
        <?php unset($_SESSION['go_to']); ?>
        $('#dynamic-content').hide();
        $('#loading-spinner').show();
        if (goTo == 'modificar_perfil.php') {
            $('#dynamic-content').load(goTo, function() {
                cargarInformacionExistente();
            });

            $('#loading-spinner').hide();
            $('#dynamic-content').show();
        }

    });
</script>