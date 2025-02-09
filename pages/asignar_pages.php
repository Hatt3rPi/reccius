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
        <form id="selectUsers" class="container">
        </form>
        <button type="submit" form="selectUsers" class="btn btn-primary mt-3">Guardar</button>
    </div>
</body>

<script>
    var roles, pages, users, orifinalRelationships;

    var addClasses = (element, classes) => {
        classes.forEach(className => element.classList.add(className));
    };

    function cleanAllChecks() {
        var checks = document.querySelectorAll('.usuario_check')
        checks.forEach(input => {
            input.checked = false;
        });
    };

    function onPageChange() {
        document.getElementById('rolSelect').addEventListener('change', function(e) {
            if (this.value) {
                cleanAllChecks();
                fetch('./backend/paginas/pagesBe.php?id_pagina=' + this.value)
                    .then(response => response.json())
                    .then(data => {
                        originalRelationships = data;
                        console.log({originalRelationships});
                        
                        if (data.users && Array.isArray(data.users)) {
                            data.users.forEach(user => {
                                const checkbox = document.getElementById(`user_${user.usuario_id}`);
                                if (checkbox) {
                                    checkbox.checked = true;
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

            // Contenedor para los botones a la derecha
            var btnContainer = document.createElement('span');
            btnContainer.style.float = 'right';

            var selectAllBtn = document.createElement('button');
            selectAllBtn.type = 'button';
            selectAllBtn.textContent = 'Seleccionar todo';
            addClasses(selectAllBtn, ['btn', 'btn-sm', 'btn-dark', 'me-2']);

            var deselectAllBtn = document.createElement('button');
            deselectAllBtn.type = 'button';
            deselectAllBtn.textContent = 'Deseleccionar todo';
            addClasses(deselectAllBtn, ['btn', 'btn-sm', 'btn-dark']);

            selectAllBtn.addEventListener('click', e => {
                e.preventDefault();
                details.querySelectorAll(`input[data-role="${role}"]`).forEach(input => {
                    input.checked = true;
                });
            });
            deselectAllBtn.addEventListener('click', e => {
                e.preventDefault();
                details.querySelectorAll(`input[data-role="${role}"]`).forEach(input => {
                    input.checked = false;
                });
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

                    // Add classes
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

        // Mantener solo la opción por defecto
        selectElement.innerHTML = '<option value="" selected>Selecciona una pagina</option>';

        // Agrupar páginas por tipo
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
            optgroup.label = `Tipo ${type}`; // Puedes personalizar las etiquetas según necesites

            // Agregar las páginas como opciones dentro del optgroup
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

            setUsers(users);
            setPages(pages);
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

    document.getElementById('selectUsers').addEventListener('submit', function(e) {
        e.preventDefault();
        var pageIdSelected = document.getElementById('rolSelect').value
        const selectedData = [...document.querySelectorAll('.usuario_check')].map(chk => ({
            id_usuario: chk.value,
            checked: chk.checked
        }));
        console.log({pageIdSelected,selectedData});
        /*
            {
                "pageIdSelected": "6",
                "selectedData": [
                    {
                        "id_usuario": "32",
                        "checked": false
                    },
                    {
                        "id_usuario": "55",
                        "checked": true
                    },
                    {
                        "id_usuario": "56",
                        "checked": false
                    },
                    {
                        "id_usuario": "68",
                        "checked": false
                    },
                    {
                        "id_usuario": "48",
                        "checked": false
                    },
                    {
                        "id_usuario": "53",
                        "checked": true
                    },
                    {
                        "id_usuario": "60",
                        "checked": false
                    },
                    {
                        "id_usuario": "67",
                        "checked": false
                    },
                    {
                        "id_usuario": "2",
                        "checked": false
                    },
                    {
                        "id_usuario": "37",
                        "checked": false
                    },
                    {
                        "id_usuario": "66",
                        "checked": false
                    },
                    {
                        "id_usuario": "38",
                        "checked": false
                    },
                    {
                        "id_usuario": "39",
                        "checked": false
                    },
                    {
                        "id_usuario": "5",
                        "checked": false
                    },
                    {
                        "id_usuario": "40",
                        "checked": false
                    },
                    {
                        "id_usuario": "41",
                        "checked": false
                    },
                    {
                        "id_usuario": "71",
                        "checked": false
                    },
                    {
                        "id_usuario": "69",
                        "checked": false
                    },
                    {
                        "id_usuario": "65",
                        "checked": false
                    },
                    {
                        "id_usuario": "64",
                        "checked": false
                    },
                    {
                        "id_usuario": "45",
                        "checked": true
                    },
                    {
                        "id_usuario": "8",
                        "checked": true
                    },
                    {
                        "id_usuario": "33",
                        "checked": true
                    },
                    {
                        "id_usuario": "47",
                        "checked": false
                    },
                    {
                        "id_usuario": "70",
                        "checked": false
                    },
                    {
                        "id_usuario": "63",
                        "checked": false
                    },
                    {
                        "id_usuario": "73",
                        "checked": true
                    },
                    {
                        "id_usuario": "42",
                        "checked": false
                    },
                    {
                        "id_usuario": "52",
                        "checked": false
                    },
                    {
                        "id_usuario": "62",
                        "checked": false
                    },
                    {
                        "id_usuario": "59",
                        "checked": false
                    },
                    {
                        "id_usuario": "49",
                        "checked": false
                    },
                    {
                        "id_usuario": "72",
                        "checked": false
                    },
                    {
                        "id_usuario": "54",
                        "checked": false
                    },
                    {
                        "id_usuario": "46",
                        "checked": false
                    },
                    {
                        "id_usuario": "50",
                        "checked": false
                    },
                    {
                        "id_usuario": "58",
                        "checked": false
                    },
                    {
                        "id_usuario": "43",
                        "checked": false
                    },
                    {
                        "id_usuario": "57",
                        "checked": false
                    },
                    {
                        "id_usuario": "51",
                        "checked": false
                    },
                    {
                        "id_usuario": "61",
                        "checked": false
                    },
                    {
                        "id_usuario": "44",
                        "checked": false
                    },
                    {
                        "id_usuario": "34",
                        "checked": true
                    },
                    {
                        "id_usuario": "36",
                        "checked": false
                    },
                    {
                        "id_usuario": "35",
                        "checked": false
                    },
                    {
                        "id_usuario": "3",
                        "checked": false
                    }
                ]
            }
        */
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

    cargaInicial()
</script>

</html>