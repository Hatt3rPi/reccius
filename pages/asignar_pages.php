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
            padding: 1rem;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            margin-bottom: 0.5rem;
        }

        .details-container summary:hover {
            background-color: #e9ecef;
        }

        .details-container main {
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }

        .details-container table {
            width: 100%;
        }

        .details-container details[open] summary {
            margin-bottom: 1rem;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
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
    var QSALL = (id) => document.querySelectorAll(id);

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
                <details class="details-container shadow-sm mb-3" id="detail-${role.id}">
                    <summary class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">${role.nombre.replaceAll('_',' ')}</span>
                        <small class="text-muted">${role.descripcion || ''}</small>
                    </summary>
                    <main id="detail-role-${role.id}" class="bg-white">
                    </main>
                </details>
            `;
        });
    }


    function renderUserRoleSelector(user) {
        var pageRolesOpts = pageRoles.map(role => 
        `<option ${user.rol_id == role.id && 'selected'} value="${role.id}">${role.nombre}</option>`).join('');
        return `
            <select 
                id="change-role-module-${user.usuario_id}"
                data-user-id="${user.usuario_id}" 
                class="form-control select-role-module" 
                name="modulo" 
                style="width: 100%;"
            >
                <option value="" selected disabled>
                    Selecciona un módulo
                </option>
                ${pageRolesOpts.join('') }
            </select>
        `;
    }
    function renderUserInRole(user) {
        return `
            <tr>
                <td class="align-middle">${user.usuario_nombre}</td>
                <td class="align-middle">${user.usuario_correo}</td>
                <td class="align-middle">${user.usuario_cargo}</td>
                <td class="align-middle text-center">
                    ${renderUserRoleSelector(user)}
                </td>
            </tr>
        `;
    }

    function getModuleRelationships(module_id) {
        fetch(`./backend/paginas/pagesBe.php?module_id=${module_id}`)
            .then(response => response.json())
            .then((data) => {
                pageRoles.forEach(role => {
                    const detailRole = GEBI(`detail-role-${role.id}`);
                    detailRole.innerHTML = `
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Cargo</th>
                                        <th class="text-center" style="width: 100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="role-body-${role.id}">
                                </tbody>
                            </table>
                        </div>
                    `;
                });

                data.forEach(user => {
                    const roleBody = GEBI(`role-body-${user.rol_id}`);
                    if (roleBody) {
                        roleBody.innerHTML += renderUserInRole(user);
                    }
                });
                QSALL(".select-role-module").forEach(select => {
                    select.addEventListener('change', function() {
                        const userId = this.dataset.userId;
                        console.log({dataset:this.dataset});
                        
                        const rolId = this.value;
                        fetch('./backend/paginas/pagesBe.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                fn: 'changeUserRole',
                                userId,
                                moduleId: gModuleId,
                                rolId
                            })
                        }).finally(() =>
                            getModuleRelationships(gModuleId))
                    });
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