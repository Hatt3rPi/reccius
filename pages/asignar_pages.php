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
                <input type="text" class="form-control col-6" id="searchUser" placeholder="Buscar usuario" disabled>
                <button class="btn btn-primary col-2" onclick="searchUsers()" id="searchUserButton" disabled>Buscar</button>
            </div>
        </div>
        <div class="container mb-3" id="tableAddUserContainer" style="display: none;">
            <div class="w-100 d-flex justify-content-center" id="tableAddUser">
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
    var pageRoles, modules, users, searchHash, originalRelationships, gModuleId;
    var GEBI = (id) => document.getElementById(id);

    var addClasses = (element, classes) => {
        classes.forEach(className => element.classList.add(className));
    };

    function setModules(modules) {
        var moduleSelect = GEBI('moduleSelect');
        moduleSelect.innerHTML = '<option value="" selected disabled>Selecciona un módulo</option>';
        modules.forEach(module => {
            var option = document.createElement('option');
            option.value = module.id;
            option.textContent = module.nombre;
            moduleSelect.appendChild(option);
        });
    }

    function searchUsers() {
        var tableAddUserContainer = GEBI('tableAddUserContainer');
        tableAddUserContainer.style.display = 'block';
        var searchUser = GEBI('searchUser').value;
        var tableAddUser = GEBI('tableAddUser');
        var tableAddUserBody = GEBI('tableAddUserBody');

        tableAddUser.style.display = 'none';
        if (!searchUser) {
            return;
        }
        fetch(`./backend/usuario/getUser.php?nombre=${searchUser}&module_id=${gModuleId}`)
            .then(response => response.json())
            .then((data) => {
                tableAddUser.style.display = 'flex';
                tableAddUserBody.innerHTML = '';
                searchHash = {};
                data.forEach((el) => {
                    searchHash[el.id] = el;
                    tableAddUserBody.innerHTML += `
                        <tr>
                            <td>${el.nombre}</td>
                            <td>${el.correo}</td>
                            <td>${el.cargo}</td>
                            <td>
                                <button onclick="addUser(${el.id})" class="btn btn-primary">
                                    +
                                </button>
                            </td>
                        </tr>
                    `;
                })

            });
    }

    function addUser(userId) {
        fetch('./backend/paginas/pagesBe.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                fn: 'addUserToModuleRelationship',
                userId,
                moduleId: gModuleId
            })
        }).finally(() =>
            getModuleRelationships(gModuleId))
    }

    function setPageRoles(pR) {
        var detailsContainer = GEBI('details-container');
        detailsContainer.innerHTML = '';
        pR.forEach((role) => {
            detailsContainer.innerHTML += `
                <details class="details-container" id="detail-${role.id}">
                    <summary>${role.nombre.replaceAll('_',' ')}</summary>
                    <main id="detail-role-${role.id}">
                        
                    </main>
                </details>
            `;
        });
    }

    function renderUserInRole(user) {
        return `
            <tr>
                <td>${user.usuario_nombre}</td>
                <td>${user.usuario_correo}</td>
                <td>${user.usuario_cargo}</td>
                <td>
                    <button onclick="removeUser(${user.usuario_id}, ${user.usuario_modulo_id})" class="btn btn-danger">
                        x
                    </button>
                </td>
            </tr>
        `;
    }

    function getModuleRelationships(module_id) {
        fetch(`./backend/paginas/pagesBe.php?module_id=${module_id}`)
            .then(response => response.json())
            .then((data) => {
                // Limpiar todas las tablas de roles
                pageRoles.forEach(role => {
                    const detailRole = GEBI(`detail-role-${role.id}`);
                    detailRole.innerHTML = `
                        <table>
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Cargo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="role-body-${role.id}">
                            </tbody>
                        </table>
                    `;
                });

                // Distribuir usuarios según su rol
                data.forEach(user => {
                    const roleBody = GEBI(`role-body-${user.rol_id}`);
                    if (roleBody) {
                        roleBody.innerHTML += renderUserInRole(user);
                    }
                });
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

    GEBI('moduleSelect').addEventListener('change',
        async function() {
            gModuleId = this.value;
            if (!gModuleId) {
                return;
            }
            GEBI("searchUser").disabled = false;
            GEBI("searchUserButton").disabled = false;

            getModuleRelationships(gModuleId);
        });

    cargaInicial();
</script>

</html>