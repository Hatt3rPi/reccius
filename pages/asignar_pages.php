<?php
// archivo: pages/asignar_pages.php
session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" href="../assets/css/Listados.css">
    <style>
        .details-container summary {
            list-style: none;
            cursor: pointer;
            padding-left: 1rem;
            position: relative;
        }

        .details-container summary::-webkit-details-marker {
            display: none;
        }
    </style>
</head>

<body>
    <div class="form-container m-0">
        <h1>Administración / Gestión de Módulos</h1>
        <br><br>
        <div class="container">
            <select id="moduleSelect" class="form-control" name="modulo" style="width: 100%;">
                <option value="" selected>Selecciona un módulo</option>
            </select>
        </div>
        <form id="selectUsers" class="container">
        </form>
        <div class="container">
            <button type="submit" form="selectUsers" class="btn btn-primary mt-3">Guardar</button>
        </div>
    </div>
</body>

<script>
    var roles, modules, users, originalRelationships;

    var addClasses = (element, classes) => {
        classes.forEach(className => element.classList.add(className));
    };

    function cleanAllChecks() {
        var checks = document.querySelectorAll('.usuario_check');
        checks.forEach(input => {
            input.checked = false;
        });
    };

    function updateRoleCounter(role) {
        const counter = document.getElementById(`counter_${role}`);
        if (counter) {
            const total = document.querySelectorAll(`input[data-role="${role}"]`).length;
            const checkedCount = document.querySelectorAll(`input[data-role="${role}"]:checked`).length;
            counter.textContent = ` (${checkedCount}/${total})`;
        }
    }



    async function cargaInicial() {
        try {
            var [modulesResponse] = await Promise.all([
                fetch('./backend/paginas/pagesBe.php')
            ]);

            if (!modulesResponse.ok) {
                throw new Error('Error en una o más respuestas del servidor');
            }

            [dataModules] = await Promise.all([
                modulesResponse.json()
            ]);

            modules = dataModules.modules || [];

            setModules(modules);

            console.log('Datos cargados:', {
                modules
            });

        } catch (error) {
            console.error('Error al cargar datos:', error);
        }
    }


    cargaInicial();
</script>

</html>
