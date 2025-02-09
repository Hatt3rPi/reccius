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
        .details-container summary::before {
            content: "►";
            position: absolute;
            left: 0;
        }
        details[open] .details-container summary::before {
            content: "▼";
        }
    </style>
</head>

<body>
    <div class="form-container m-0">
        <h1>Administración / Gestión de Paginas </h1>
        <br><br>
        <select id="rolSelect" class="form-control" name="rol" style="width: 100%;">
            <option value="" selected>Selecciona una pagina</option>
        </select>
        <form id="selectUsers" class="container">
        </form>
        <button type="submit" form="selectUsers" class="btn btn-primary mt-3">Guardar</button>
    </div>
</body>

<script>
    function setUsers(users) {
        if (!Array.isArray(users) || users.length === 0) {
            console.error('No se proporcionaron usuarios válidos');
            return;
        }

        const form = document.getElementById('selectUsers');
        if (!form) {
            console.error('No se encontró el formulario de usuarios');
            return;
        }

        form.innerHTML = '';

        const usersByRole = users.reduce((groups, user) => {
            const role = user.rol || 'Sin Rol';
            if (!groups[role]) {
                groups[role] = [];
            }
            groups[role].push(user);
            return groups;
        }, {});

        Object.entries(usersByRole).sort().forEach(([role, roleUsers], i) => {
            const details = document.createElement('details');
            const summary = document.createElement('summary');
            const container = document.createElement('div');
            
            
            details.classList.add('border');
            details.classList.add('border-secondary');
            details.classList.add('mt-3');
            details.classList.add('details-container');
            
            summary.textContent = role.charAt(0).toUpperCase() + role.slice(1).toLowerCase();
            summary.classList.add('p-3');

            // Contenedor para los botones a la derecha
            const btnContainer = document.createElement('span');
            btnContainer.style.float = 'right';

            const selectAllBtn = document.createElement('button');
            selectAllBtn.type = 'button';
            selectAllBtn.textContent = 'Seleccionar todo';
            selectAllBtn.classList.add('btn', 'btn-sm', 'btn-link', 'me-2');
            selectAllBtn.addEventListener('click', e => {
                e.preventDefault();
                details.querySelectorAll(`input[data-role="${role}"]`).forEach(input => {
                    input.checked = true;
                });
            });

            const deselectAllBtn = document.createElement('button');
            deselectAllBtn.type = 'button';
            deselectAllBtn.textContent = 'Deseleccionar todo';
            deselectAllBtn.classList.add('btn', 'btn-sm', 'btn-link');
            deselectAllBtn.addEventListener('click', e => {
                e.preventDefault();
                details.querySelectorAll(`input[data-role="${role}"]`).forEach(input => {
                    input.checked = false;
                });
            });

            btnContainer.appendChild(selectAllBtn);
            btnContainer.appendChild(deselectAllBtn);
            summary.appendChild(btnContainer);
            
            container.classList.add('row');
            container.classList.add('p-3');
            
            details.appendChild(summary);
            details.appendChild(document.createElement('hr'));
            details.appendChild(container);
            
            if (i === 0) {
                details.open = true;
            }

            roleUsers
                .sort((a, b) => a.nombre.localeCompare(b.nombre))
                .forEach(user => {
                    const label = document.createElement('label');
                    const input = document.createElement('input');
                    
                    // Add classes individually
                    label.classList.add('col-12');
                    label.classList.add('col-md-6');
                    label.classList.add('col-lg-4');

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

        const selectElement = document.getElementById('rolSelect');
        if (!selectElement) {
            console.error('No se encontró el elemento select');
            return;
        }

        // Mantener solo la opción por defecto
        selectElement.innerHTML = '<option value="" selected>Selecciona una pagina</option>';

        // Agrupar páginas por tipo
        const pagesByType = pages.reduce((groups, page) => {
            const type = page.tipo;
            if (!groups[type]) {
                groups[type] = [];
            }
            groups[type].push(page);
            return groups;
        }, {});

        // Crear optgroups para cada tipo de página
        Object.entries(pagesByType).sort().forEach(([type, typePages]) => {
            const optgroup = document.createElement('optgroup');
            optgroup.label = `Tipo ${type}`; // Puedes personalizar las etiquetas según necesites

            // Agregar las páginas como opciones dentro del optgroup
            typePages.forEach(page => {
                const option = document.createElement('option');
                option.value = page.id;
                option.textContent = page.nombre;
                optgroup.appendChild(option);
            });

            selectElement.appendChild(optgroup);
        });

        // Agregar evento de cambio
        selectElement.addEventListener('change', function() {
            const selectedPageId = this.value;
            if (selectedPageId) {
                // Aquí puedes agregar la lógica para cargar los usuarios 
                // que tienen acceso a esta página
                console.log('Página seleccionada:', selectedPageId);
                // TODO: Implementar carga de usuarios por página
            }
        });
    }

    async function cargarUsuarios() {
        try {
            const [usuariosResponse, rolesResponse, pagesResponse] = await Promise.all([
                fetch('./backend/administracion_usuarios/obtener_usuariosBE.php'),
                fetch('./backend/administracion_usuarios/obtener_rolesBE.php'),
                fetch('./backend/paginas/pagesBe.php')
            ]);

            if (!usuariosResponse.ok || !rolesResponse.ok || !pagesResponse.ok) {
                throw new Error('Error en una o más respuestas del servidor');
            }
            const [usuarios, roles, pages] = await Promise.all([
                usuariosResponse.json(),
                rolesResponse.json(),
                pagesResponse.json()
            ]);

            setUsers(usuarios.data);
            setPages(pages);

        } catch (error) {
            console.error('Error al cargar datos:', error);
        }
    }

    cargarUsuarios()
</script>

</html>