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
        <div class="container mb-3">
            <select id="moduleSelect" class="form-control" name="modulo" style="width: 100%;">
                <option value="" selected>Selecciona un módulo</option>
            </select>
        </div>
        <div class="container mb-3">
            <div class="form-group" style="display: flex;gap: 4px;">
                <input type="text" class="form-control col-6" id="searchUser" placeholder="Buscar usuario">
                <button class="btn btn-primary col-2" onclick="searchUsers()">Buscar</button>
            </div>
        </div>
        <div class="container mb-3">
            <div class="w-100 d-flex justify-content-center" id="tableAddUser" style="display: none;">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Cargo</th>
                            <th>Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody id="tableAddUserBody">
                    </tbody>
                </table>
            </div>
        </div>

        <section class="container" id="details-container">




        </section>

        <div class="container">
            <button class="btn btn-primary mt-3">Guardar</button>
        </div>
    </div>
</body>

<script>
    var pageRoles, modules, users, originalRelationships;

    var addClasses = (element, classes) => {
        classes.forEach(className => element.classList.add(className));
    };

    function setModules(modules) {
        var moduleSelect = document.getElementById('moduleSelect');
        moduleSelect.innerHTML = '<option value="" selected disabled>Selecciona un módulo</option>';
        modules.forEach(module => {
            var option = document.createElement('option');
            option.value = module.id;
            option.textContent = module.nombre;
            moduleSelect.appendChild(option);
        });
    }

    function searchUsers() {
        var searchUser = document.getElementById('searchUser').value;
        var tableAddUser = document.getElementById('tableAddUser');
        var tableAddUserBody = document.getElementById('tableAddUserBody');

        tableAddUser.style.display = 'none';
        if (!searchUser) {
            return;
        }
        fetch(`./backend/usuario/getUser.php?nombre=${searchUser}`)
            .then(response => response.json())
            .then((data) => {
                tableAddUser.style.display = 'flex';
                tableAddUserBody.innerHTML = '';
                data.forEach((el) => {
                    tableAddUserBody.innerHTML += `
                        <tr>
                            <td>${el.nombre}</td>
                            <td>${el.correo}</td>
                            <td>${el.cargo}</td>
                            <td>
                                <button onclick="addUser(${el})"> >
                                    +
                                </button>
                            </td>
                        </tr>
                    `;
                })

            });
    }

    function addUser(user) {
        console.log(user);
    }

    function setPageRoles(pR) {
        var detailsContainer = document.getElementById('details-container');
        detailsContainer.innerHTML = '';
        pR.forEach((role) => {
            detailsContainer.innerHTML += `
                <details class="details-container" id="detail-${role.id}">
                    <summary>${role.nombre}</summary>
                    <main id="detail-role-${role.id}">
                        
                    </main>
                </details>
            `;
        });
    }

    function setUsers(users) {
        var tableAddUserBody = document.getElementById('tableAddUserBody');
        tableAddUserBody.innerHTML = '';
        users.forEach((el) => {
            tableAddUserBody.innerHTML += `
                <tr>
                    <td>${el.nombre}</td>
                    <td>${el.correo}</td>
                    <td>${el.cargo}</td>
                    <td>
                        <button onclick="addUser(${el})" class="btn btn-primary">
                            +
                    </td>
                </tr>
            `;
        });
    }

    function getModuleRelationships(module_id) {
        fetch(`./backend/paginas/pagesBe.php?module_id=${module_id}`)
            .then(response => response.json())
            .then((data) => {
                console.log();
            });
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
            pageRoles = dataModules.pageRoles || [];
            setModules(modules);
            setPageRoles(pageRoles);
            console.log('Datos cargados:', {
                modules
            });

        } catch (error) {
            console.error('Error al cargar datos:', error);
        }
    }

    document.getElementById('moduleSelect').addEventListener('change', async function() {
        var module_id = this.value;
        if (!module_id) {
            return;
        }
        getModuleRelationships(module_id);
    });

    cargaInicial();
</script>

</html>