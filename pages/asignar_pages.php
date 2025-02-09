<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" href="../assets/css/Listados.css">
</head>

<body>
    <div class="form-container">
        <h1>Administración / Gestión de Paginas </h1>
        <br><br>
        <h2 class="section-title">Listado de Usuarios y Roles:</h2>
        <select id="rolSelect" name="rol" style="width: 100%;">
            <option value="" selected >Selecciona una pagina</option>
        </select>
        <form id="selectUsers">
            <button type="submit" class="">Guardar</button>
        </form>
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

        // Limpiar contenido existente
        form.innerHTML = '';

        // Agrupar usuarios por rol
        const usersByRole = users.reduce((groups, user) => {
            const role = user.rol || 'Sin Rol';
            if (!groups[role]) {
                groups[role] = [];
            }
            groups[role].push(user);
            return groups;
        }, {});

        // Crear fieldsets para cada grupo de roles
        Object.entries(usersByRole).forEach(([role, roleUsers]) => {
            const fieldset = document.createElement('fieldset');
            const legend = document.createElement('legend');
            legend.textContent = role.charAt(0).toUpperCase() + role.slice(1).toLocaleLowerCase(); // Capitalizar primera letra
            fieldset.appendChild(legend);

            // Crear checkboxes para cada usuario
            roleUsers.forEach(user => {
                const label = document.createElement('label');
                const input = document.createElement('input');

                input.type = 'checkbox';
                input.className = 'usuario_check';
                input.value = user.id;
                input.id = `user_${user.id}`;

                label.appendChild(input);
                label.appendChild(document.createTextNode(` ${user.nombre} (${user.usuario})`));
                fieldset.appendChild(label);
            });

            form.appendChild(fieldset);
        });

        // Agregar botón de guardar
        const submitButton = document.createElement('button');
        submitButton.type = 'submit';
        submitButton.textContent = 'Guardar';
        form.appendChild(submitButton);
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
            const type = page.id_tipo_pagina;
            if (!groups[type]) {
                groups[type] = [];
            }
            groups[type].push(page);
            return groups;
        }, {});

        // Crear optgroups para cada tipo de página
        Object.entries(pagesByType).forEach(([type, typePages]) => {
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

    // Inicializar al cargar la página
    document.addEventListener('DOMContentLoaded', cargarUsuarios);
</script>

</html>