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
        <h1>Administración / Gestión de Paginas </h1>
        <br><br>
        <div class="container">
            <select id="rolSelect" class="form-control" name="rol" style="width: 100%;">
                <option value="" selected>Selecciona una pagina</option>
            </select>
        </div>
        <div class="container">
            <select id="moduleSelect" class="form-control" name="module" style="width: 100%;">
                <option value="" selected>Selecciona una modulo</option>
            </select>
        </div>
        <div class="container">
            <input id="search" class="form-control" form="users"  name="search" type="search" style="width: 100%;"/>
            <datalist id="users">

            </datalist>
        </div>
        <form id="selectUsers" class="container">
        </form>
        <div class="container">
            <button type="submit" form="selectUsers" class="btn btn-primary mt-3">Guardar</button>
        </div>
    </div>
</body>

<script>
    var roles, pages, users, modules;

    var addClasses = (element, classes) => {
        classes.forEach(className => element.classList.add(className));
    };

    function cleanAllChecks() {
        var checks = document.querySelectorAll('.usuario_check')
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

    function onPageChange() {
        document.getElementById('rolSelect').addEventListener('change', function(e) {
            if (this.value) {
                cleanAllChecks();
                const roles = new Set([...document.querySelectorAll('.usuario_check')].map(chk => chk.dataset.role));
                roles.forEach(updateRoleCounter);

                fetch('./backend/paginas/pagesBe.php?id_pagina=' + this.value)
                    .then(response => response.json())
                    .then(data => {
                        originalRelationships = data;
                        
                        if (data && Array.isArray(data)) {
                            data.forEach(userCheck => {
                                const checkbox = document.getElementById(`user_${userCheck.usuario_id}`);
                                if (checkbox) {
                                    checkbox.checked = true;
                                    updateRoleCounter(checkbox.dataset.role);
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar relaciones:', error);
                    });
            }
        });
    }

    function setUsers(users) {
        if (!Array.isArray(users) || users.length === 0) {
            console.error('No se proporcionaron usuarios válidos');
            return;
        }

        var form = document.getElementById('selectUsers');
        if (!form) {
            console.error('No se encontró el formulario de usuarios');
            return;
        }

        form.innerHTML = '';

        var usersByRole = users.reduce((groups, user) => {
            var role = user.rol || 'Sin Rol';
            if (!groups[role]) {
                groups[role] = [];
            }
            groups[role].push(user);
            return groups;
        }, {});

        Object.entries(usersByRole).sort().forEach(([role, roleUsers], i) => {
            var details = document.createElement('details');
            var summary = document.createElement('summary');
            var container = document.createElement('div');
            var counter = document.createElement('span');

            addClasses(details, ['border', 'border-secondary', 'rounded-lg', 'mt-3', 'details-container']);
            summary.textContent = role.charAt(0).toUpperCase() + role.slice(1).toLowerCase();
            addClasses(summary, ['px-3', 'py-2']);

            counter.id = `counter_${role}`;
            summary.appendChild(counter);

            var btnContainer = document.createElement('span');
            btnContainer.style.float = 'right';

            var selectAllBtn = document.createElement('button');
            selectAllBtn.type = 'button';
            selectAllBtn.textContent = 'Seleccionar';
            addClasses(selectAllBtn, ['btn', 'btn-sm', 'btn-dark']);

            var deselectAllBtn = document.createElement('button');
            deselectAllBtn.type = 'button';
            deselectAllBtn.textContent = 'Deseleccionar';
            addClasses(deselectAllBtn, ['btn', 'btn-sm', 'btn-dark', 'ml-2']);

            selectAllBtn.addEventListener('click', e => {
                e.preventDefault();
                details.querySelectorAll(`input[data-role="${role}"]`).forEach(input => {
                    input.checked = true;
                });
                updateRoleCounter(role);
            });
            deselectAllBtn.addEventListener('click', e => {
                e.preventDefault();
                details.querySelectorAll(`input[data-role="${role}"]`).forEach(input => {
                    input.checked = false;
                });
                updateRoleCounter(role);
            });

            btnContainer.appendChild(selectAllBtn);
            btnContainer.appendChild(deselectAllBtn);
            summary.appendChild(btnContainer);

            addClasses(container, ['row', 'p-3']);

            details.appendChild(summary);
            details.appendChild(document.createElement('hr'));
            details.appendChild(container);

            if (i === 0) {
                details.open = true;
            }

            roleUsers
                .sort((a, b) => a.nombre.localeCompare(b.nombre))
                .forEach(user => {
                    var label = document.createElement('label');
                    var input = document.createElement('input');

                    addClasses(label, ['form-check', 'col-12', 'col-md-6', 'col-lg-4']);
                    input.type = 'checkbox';
                    input.className = 'usuario_check';
                    input.dataset.role = role;
                    input.value = user.id;
                    input.id = `user_${user.id}`;

                    label.appendChild(input);
                    label.appendChild(document.createTextNode(` ${user.nombre} (${user.usuario})`));
                    container.appendChild(label);
                });

            form.appendChild(details);

            updateRoleCounter(role);
        });
    }

    function setPages(pages) {
        if (!Array.isArray(pages) || pages.length === 0) {
            console.error('No se proporcionaron páginas válidas');
            return;
        }

        var selectElement = document.getElementById('rolSelect');
        if (!selectElement) {
            console.error('No se encontró el elemento select');
            return;
        }

        selectElement.innerHTML = '<option value="" selected>Selecciona una pagina</option>';

        var pagesByType = pages.reduce((groups, page) => {
            var type = page.tipo;
            if (!groups[type]) {
                groups[type] = [];
            }
            groups[type].push(page);
            return groups;
        }, {});

        // Crear optgroups para cada tipo de página
        Object.entries(pagesByType).sort().forEach(([type, typePages]) => {
            var optgroup = document.createElement('optgroup');
            optgroup.label = `Tipo ${type}`; 

            typePages.forEach(page => {
                var option = document.createElement('option');
                option.value = page.id;
                option.textContent = page.nombre;
                optgroup.appendChild(option);
            });

            selectElement.appendChild(optgroup);
        });
        onPageChange()
    }
    function modulesHandler() {
        var selectElement = document.getElementById('moduleSelect');
        if (!selectElement) {
            console.error('No se encontró el elemento select');
            return;
        }

        selectElement.addEventListener('change', function(e) {
            if (this.value) {
             console.log('Modulo seleccionado:', this.value);

            }
        });
    }	
    function setModules(modules) {
        if (!Array.isArray(modules) || modules.length === 0) {
            console.error('No se proporcionaron modulos válidos');
            return;
        }

        var selectElement = document.getElementById('moduleSelect');
        if (!selectElement) {
            console.error('No se encontró el elemento select');
            return;
        }

        selectElement.innerHTML = '<option value="" selected>Selecciona una modulo</option>';

        modules.forEach(module => {
            var option = document.createElement('option');
            option.value = module.id;
            option.textContent = module.nombre;
            selectElement.appendChild(option);
        });
        modulesHandler()
    }

    async function cargaInicial() {
        try {
            var [usuariosResponse, rolesResponse, pagesResponse] = await Promise.all([
                fetch('./backend/administracion_usuarios/obtener_usuariosBE.php'),
                fetch('./backend/administracion_usuarios/obtener_rolesBE.php'),
                fetch('./backend/paginas/pagesBe.php')
            ]);

            if (!usuariosResponse.ok || !rolesResponse.ok || !pagesResponse.ok) {
                throw new Error('Error en una o más respuestas del servidor');
            }

            [users, roles, pages] = await Promise.all([
                usuariosResponse.json(),
                rolesResponse.json(),
                pagesResponse.json()
            ]);

            users = users.data || users;
            modules = pages.modules || [];

            setUsers(users);
            setPages(pages.pages);
            roles.unshift({
                id: "-1",
                nombre: "Sin Rol"
            });

            console.log('Datos cargados:', {
                users,
                roles,
                pages
            });

        } catch (error) {
            console.error('Error al cargar datos:', error);
        }
    }
    document.getElementById('selectUsers').addEventListener('change', function(e) {
        if (e.target.classList.contains('usuario_check')) {
            updateRoleCounter(e.target.dataset.role);
        }
    });
    
    document.getElementById('selectUsers').addEventListener('submit', function(e) {
        e.preventDefault();
        var pageIdSelected = document.getElementById('rolSelect').value
        const selectedData = [...document.querySelectorAll('.usuario_check')].map(chk => ({
            id_usuario: chk.value,
            checked: chk.checked
        }));
        fetch('./backend/paginas/pagesBe.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    usuarios: selectedData,
                    pagina_id: pageIdSelected
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data);
            })
            .catch(error => {
                console.error('Error al guardar relaciones:', error);
            });
    });
    document.getElementById('search').addEventListener('input', function(e) {
        var search = this.value;

        fetch('./backend/usuario/getUser.php?nombre=' + search)
            .then(response => response.json())
            .then(data => {
                var users = data;
                var datalist = document.getElementById('users');
                datalist.innerHTML = '';

                users.forEach(user => {
                    var option = document.createElement('option');
                    option.value = JSON.stringify(user);
                    option.innerHTML = `${user.nombre}`;
                    datalist.appendChild(option);
                });
            });
        
    });

    cargaInicial()
</script>

</html>