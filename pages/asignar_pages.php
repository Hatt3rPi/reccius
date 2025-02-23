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
        <div class="container">
            <h4> Administrar roles de acceso a paginas </h4>
        </div>
        <div class="container mb-3">
            <div class="table-responsive w-100 d-flex justify-content-center" id="tablePageRolManaget">
                <table class="table table-hover table-striped m-0">
                    <thead class=table-light>
                        <tr id="tableHeaderPageRolManaget">
                        </tr>
                    </thead>
                    <tbody id="tableBodyPageRolManaget">
                    </tbody>
                </table>
            </div>
            <button id="submitPageRolManaget" disabled class="btn btn-primary mt-3">Guardar</button>
        </div>
        <div class="container">
            <h4> Añadir usuarios al modulo </h4>
        </div>
        <div class="container mb-3">
            <div class="form-group d-flex justify-content-center" style="gap: 4px;">
                <input type="text" class="form-control col-6" id="searchUser" placeholder="Buscar usuario" disabled>
                <button class="btn btn-primary col-2" onclick="searchUsers()" id="searchUserButton" disabled>Buscar</button>
            </div>
        </div>
        <div class="container mb-3" id="tableAddUserContainer" style="display: none;">
            <div class="table-responsive w-100 d-flex justify-content-center" id="tableAddUser">
                <table class="table table-hover table-striped m-0">
                    <thead class=table-light>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Cargo</th>
                            <th class="text-center">Añadir</th>
                        </tr>
                    </thead>
                    <tbody id="tableAddUserBody">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="container">
            <h4> Administrar roles de usuario en modulo </h4>
        </div>
        <section class="container" id="details-container">
        </section>

        <div class="container">
            <button class="btn btn-primary mt-3">Guardar</button>
        </div>
    </div>
</body>

<script>
    // Variables globales
    var pageRoles, modules, users, searchHash, originalRelationships, gModuleId;
    var tableBodyPageRolManaget, tableHeaderPageRolManaget, tablePageRolManaget, submitPageRolManaget;
    // Tabla de busqueda usuarios
    var tableAddUserContainer, searchUser, tableAddUser, tableAddUserBody;


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

    function setSearchUsers(data) {
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
    }

    function searchUsers() {
        tableAddUserContainer = GEBI('tableAddUserContainer');
        tableAddUserContainer.style.display = 'block';
        searchUser = GEBI('searchUser').value;
        tableAddUser = GEBI('tableAddUser');
        tableAddUserBody = GEBI('tableAddUserBody');

        tableAddUser.style.display = 'none';
        if (!searchUser) {
            return;
        }
        fetch(`./backend/usuario/getUser.php?nombre=${searchUser}&module_id=${gModuleId}`)
            .then(response => response.json())
            .then((data) =>
                setSearchUsers(data));
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
        searchUsers();
    }

    function setPageRoles(pR) {
        var detailsContainer = GEBI('details-container');
        detailsContainer.innerHTML = '';
        pR.forEach((role) => {
            detailsContainer.innerHTML += `
                <details class="details-container shadow-sm mb-3" open id="detail-${role.id}">
                    <summary class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">${role.nombre}</span>
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
                style="width: 100%;min-width: 150px;"
            >
                <option value="" selected disabled>
                    Selecciona un módulo
                </option>
                ${pageRolesOpts }
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

    function renderTableInDetail(role) {
        return `
            <div class="table-responsive">
                <table class="table table-hover table-striped m-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Cargo</th>
                            <th class="text-center" style="width: 100px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="role-body-${role}">
                    </tbody>
                </table>
            </div>`;
    }

    function changePageRole(checkChange) {
        submitPageRolManaget.disabled = false;
    }

    function setPagesRolesManagement(pg, rel) {
        submitPageRolManaget.disabled = true;
        let relHash = rel.reduce((acc, el) => {
            if (!acc[el.pagina_id]) {
                acc[el.pagina_id] = {};
            }
            acc[el.pagina_id][el.rol_pagina_id] = true;
            return acc;
        }, {});
        tableBodyPageRolManaget.innerHTML = pg.map(page => {
            return `
                <tr>
                    <td>${page.nombre}</td>
                    ${
                        pageRoles.map(role => {
                            return `
                                <td class="text-center">
                                    <input 
                                        type="checkbox" 
                                        ${relHash[page.id] && relHash[page.id][role.id] && 'checked'}
                                        data-page-id="${page.id}"
                                        data-role-id="${role.id}"
                                        onchange="changePageRole(this)"
                                        class="checkbox-page-role"
                                    >
                                </td>
                            `;
                        }).join('')
                    }
                </tr>

            `;
        }).join('');
    }

    function getModuleRelationships(module_id) {
        fetch(`./backend/paginas/pagesBe.php?module_id=${module_id}`)
            .then(response => response.json())
            .then((data) => {
                pageRoles.forEach(role => {
                    const detailRole = GEBI(`detail-role-${role.id}`);
                    detailRole.innerHTML = renderTableInDetail(role.id);
                });
                setPagesRolesManagement(data.pages, data.rolePegeRelation)
                data.users.forEach(user => {
                    const roleBody = GEBI(`role-body-${user.rol_id}`);
                    if (roleBody) {
                        roleBody.innerHTML += renderUserInRole(user);
                    }
                });
                selectRoleModuleListener();
            });
    }

    function selectRoleModuleListener() {
        QSALL(".select-role-module").forEach(select => {
            select.addEventListener('change', function() {
                const userId = this.dataset.userId;
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
    }

    function setTablePageRolManaget(pR) {
        tableHeaderPageRolManaget.innerHTML = `
            <th>Pagina</th>
            ${
                pR.map(role => `<th>${role.nombre}</th>`).join('')
            }
        `;
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

            tablePageRolManaget = GEBI("tablePageRolManaget")
            tableBodyPageRolManaget = GEBI("tableBodyPageRolManaget")
            tableHeaderPageRolManaget = GEBI("tableHeaderPageRolManaget")
            submitPageRolManaget = GEBI("submitPageRolManaget")

            modules = dataModules.modules.map((data) => ({
                ...data,
                nombre: data.nombre.replaceAll('_', ' ').replace(/\b\w/g, c => c.toUpperCase())
            })) || [];
            pageRoles = dataModules.pageRoles.sort((a,b)=> a.orden-b.orden ).map((data) => ({
                ...data,
                nombre: data.nombre.replaceAll('_', ' ').replace(/\b\w/g, c => c.toUpperCase())
            })) || [];
            setModules(modules);
            setPageRoles(pageRoles);
            setTablePageRolManaget(pageRoles);

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


    GEBI('submitPageRolManaget').addEventListener('click',
        async function() {
            var checkboxes = QSALL('.checkbox-page-role');
            var data = [];
            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    data.push({
                        page_id: checkbox.dataset.pageId,
                        role_id: checkbox.dataset.roleId
                    });
                }
            });
            fetch('./backend/paginas/pagesBe.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    fn: 'updatePageRole',
                    data,
                    moduleId: gModuleId
                })
            }).finally(() =>
                getModuleRelationships(gModuleId)
            );

        });
    cargaInicial();
</script>

</html>