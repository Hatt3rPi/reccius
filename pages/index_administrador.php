<!DOCTYPE html>
<!-- pages/index_administrador.php -->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Productos</title>
    <link rel="stylesheet" href="../assets/css/index_administrador.css">
    <link rel="stylesheet" href="../assets/css/components/Index_components/Component_Tarea.css">
    <link rel="stylesheet" href="../assets/css/components/Index_components/Component_Calendario.css">


</head>

<body>
    <div class="container dashboard">
        <h2 class="section-title">Bienvenido, <span id="username" class="username"> 
            <?php echo $_SESSION['usuario']; ?>
        </span>!</h2>
        <div class="grid-container">
            <div class="grid-item clima">
                <?php include 'components/index/clima.php'; ?>
            </div>
            <div class="grid-item tareas">
                <?php include 'components/index/tareas.php'; ?>
            </div>
            <div class="grid-item calendario">
                <?php include 'components/index/calendario.php'; ?>
            </div>
            <div class="section-title disponibilidad-productos">
                DISPONIBILIDAD DE PRODUCTOS
            </div>
            <div class="grid-item cartas">
                <?php include 'components/index/cartas.php'; ?>
            </div>

            <div class="grid-item grafo1">
                <h2>Laboratorios Presentes</h2>
                <?php include 'components/index/laboratorios.php'; ?>
            </div>
            <div class="grid-item grafo2">
                <h2>productos </h2>
                <?php include 'components/index/productos.php'; ?>
            </div>
            <div class="section-title zona-control">
                ZONA DE CONTROL
            </div>
            <div class="grid-item clas1">
            <h2>Estados: Especificaciones</h2>
                <?php include 'components/index/especificaciones.php'; ?>
            </div>
            <div class="grid-item clas2">
            <h2>Tiempo Promedio:</h2>
                <?php include 'components/index/promedio.php'; ?>
            </div>
            <div class="grid-item clas3">
            <h2>Estados: Actas</h2>
                <?php include 'components/index/actas.php'; ?>
            </div>
            <div class="grid-item clas4">
            <h2>Estados: Analisis</h2>
                <?php include 'components/index/analisis.php'; ?>
            </div>
            <div class="grid-item clas5">
            <h2>Productos Analizados:</h2>
                <?php include 'components/index/acumulados.php'; ?>
            </div>
            <div class="grid-item clas6">
            <h2>Porcentaje de Aprobación/Rechazo:</h2>
                <?php include 'components/index/porcentaje.php'; ?>
            </div>



        </div>

        
    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'backend/index/index_administrador.php',
                method: 'GET',
                success: function(response) {
                    const datos = response.data;
                },
                error: function(error) {
                    console.error('Error al obtener los datos:', error);
                }
            });

        });
        function fetchUserInfo() {
        fetch('./backend/usuario/obtener_usuarioBE.php')
            .then(response => response.json())
            .then(data => {
                if (data.usuario) {
                    document.querySelector('.username').textContent = data.nombre;
                }
            })
            .catch(error => console.error('Error:', error));
    }
    </script>
</body>

</html>