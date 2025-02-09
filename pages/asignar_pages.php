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
    // Función auxiliar para agregar clases a un elemento (versión ES5)
    function addClasses(element, classes) {
        for (var i = 0; i < classes.length; i++) {
            element.classList.add(classes[i]);
        }
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

        // Agrupar usuarios por rol
        var usersByRole = users.reduce(function(groups, user) {
            var role = user.rol || 'Sin Rol';
            if (!groups[role]) {
                groups[role] = [];
            }
            groups[role].push(user);
            return groups;
        }, {});

        // Obtener y ordenar los roles (keys) para iterar
        var roles = Object.keys(usersByRole).sort();
        roles.forEach(function(role, i) {
            var roleUsers = usersByRole[role];

            // Crear elementos para cada grupo de usuarios
            var details = document.createElement('details');
            var summary = document.createElement('summary');
            var container = document.createElement('div');

            addClasses(details, ['border', 'border-secondary', 'rounded-lg', 'mt-3', 'details-container']);
            summary.textContent = role.charAt(0).toUpperCase() + role.slice(1).toLowerCase();
            summary.classList.add('p-3');

            // Contenedor para los botones a la derecha
            var btnContainer = document.createElement('span');
            btnContainer.style.cssFloat = 'right'; // Para mayor compatibilidad con ES5, se usa cssFloat

            // Botón para seleccionar todos los usuarios
            var selectAllBtn = document.createElement('button');
            selectAllBtn.type = 'button';
            selectAllBtn.textContent = 'Seleccionar todo';
            addClasses(selectAllBtn, ['btn', 'btn-sm', 'me-2', 'btn-dark']);
            selectAllBtn.addEventListener('click', function(e) {
                e.preventDefault();
                // Selecciona todos los inputs que tengan data-role igual al rol actual
                var inputs = details.querySelectorAll('input[data-role="' + role + '"]');
                for (var j = 0; j < inputs.length; j++) {
                    inputs[j].checked = true;
                }
            });

            // Botón para deseleccionar todos los usuarios
            var deselectAllBtn = document.createElement('button');
            deselectAllBtn.type = 'button';
            deselectAllBtn.textContent = 'Deseleccionar todo';
            deselectAllBtn.classList.add('btn', 'btn-sm', 'btn-dark');
            deselectAllBtn.addEventListener('click', function(e) {
                e.preventDefault();
                var inputs = details.querySelectorAll('input[data-role="' + role + '"]');
                for (var j = 0; j < inputs.length; j++) {
                    inputs[j].checked = false;
                }
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

            // Ordenar usuarios por nombre y crear un checkbox para cada uno
            roleUsers
                .sort(function(a, b) {
                    return a.nombre.localeCompare(b.nombre);
                })
                .forEach(function(user) {
                    var label = document.createElement('label');
                    var input = document.createElement('input');

                    addClasses(label, ['form-check', 'col-12', 'col-md-6', 'col-lg-4']);
                    input.type = 'checkbox';
                    input.className = 'usuario_check';
                    // Para máxima compatibilidad, se usa setAttribute en lugar de dataset
                    input.setAttribute('data-role', role);
                    input.value = user.id;
                    input.id = 'user_' + user.id;

                    label.appendChild(input);
                    label.appendChild(document.createTextNode(' ' + user.nombre + ' (' + user.usuario + ')'));
                    container.appendChild(label);
                });

            form.appendChild(details);
        });
    }

    function cargarUsuarios() {
        Promise.all([
                fetch('./backend/administracion_usuarios/obtener_usuariosBE.php'),
                fetch('./backend/administracion_usuarios/obtener_rolesBE.php'),
                fetch('./backend/paginas/pagesBe.php')
            ])
            .then(function(responses) {
                if (!responses[0].ok || !responses[1].ok || !responses[2].ok) {
                    throw new Error('Error en una o más respuestas del servidor');
                }
                return Promise.all([
                    responses[0].json(),
                    responses[1].json(),
                    responses[2].json()
                ]);
            })
            .then(function(data) {
                var usuarios = data[0],
                    roles = data[1],
                    pages = data[2];

                setUsers(usuarios.data);
                setPages(pages);
            })
            .catch(function(error) {
                console.error('Error al cargar datos:', error);
            });
    }


    cargarUsuarios()
</script>

</html>