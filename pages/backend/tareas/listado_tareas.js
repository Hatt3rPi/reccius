import { botones } from '../assets/js/scripts_index.js';
        // Incluye aquí tu script de DataTables y las funciones para las acciones de las tareas
        var usuarioActual = "<?php echo $_SESSION['nombre']; ?>";
        var usuarioEjecutorOriginal="";

        export function cargaListadoTareas() {
            var table = $('#listado').DataTable({
                "ajax": "./backend/tareas/listado_tareasBE.php",
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                },
                "columns": [
                            {
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": '<i class="fas fa-search-plus"></i>',
                        "width": "20px"
                    },
                    { "data": "prioridad", "width": "50px" },
                    {
                        "data": "estado",
                        "title": "Estado",
                        "width": "70px",
                        "render": function(data, type, row) {
                            switch (data) {
                                case 'Activo':
                                    return '<span class="badge badge-primary">Activo</span>';
                                case 'Finalizado':
                                    return '<span class="badge badge-dark">Finalizado</span>';
                                case 'Fecha de Vencimiento cercano':
                                    return '<span class="badge badge-warning">Fecha de Vencimiento cercano</span>';
                                case 'Atrasado':
                                    return '<span class="badge badge-danger">Atrasado</span>';
                                default:
                                    return '<span class="badge">' + data + '</span>';
                            }  
                        }
                    },
                    { "data": "descripcion_tarea" },
                    {
                        "data": "usuario_creador",
                        "render": function (data, type, row) {
                            return data === usuarioActual ? '<span class="resaltar">' + data + '</span>' : data;
                        }
                    },
                    {
                        "data": "usuario_ejecutor",
                        "render": function (data, type, row) {
                            return data === usuarioActual ? '<span class="resaltar">' + data + '</span>' : data;
                        }
                    },
                    { "data": "fecha_ingreso", "width": "70px" },
                    { "data": "fecha_vencimiento", "width": "70px"  },

                    
                    {
                    title: 'id_tarea',
                    data: 'id',
                    defaultContent: '', // Puedes cambiar esto si deseas poner contenido por defecto
                    visible: false // Esto oculta la columna
                }
                ],
            });
            $('#listado tbody').on('click', 'td.details-control', function() {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // Esta fila ya está abierta - ciérrala
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Abre esta filaQ
            row.child(format(row.data())).show(); // Aquí llamas a la función que formatea el contenido expandido
            tr.addClass('shown');
        }
    });

        function format(d) {
            // `d` es el objeto de datos original para la fila
            var acciones = '<table background-color="#F6F6F6" color="#FFF" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
            acciones += '<tr><td VALIGN="TOP">Acciones:</td><td>';

            // Agrega acciones según el estado de la tarea
            if (d.estado === 'Activo' || d.estado === 'Atrasado' || d.estado === 'Fecha de Vencimiento cercano') {


                
                if (d.usuario_creador === usuarioActual) {
                    acciones += '<button class="accion-btn" title="Recordar Tarea" id="' + d.id + '" name="recordar" onclick="botones(this.id, this.name, \'tareas\')" ><i class="fas fa-envelope"></i></button><a> </a>';
                    acciones += '<button class="accion-btn" title="Cambiar Usuario Ejecutor" id="' + d.id + '" name="cambiar_usuario"  data-usuario_ejecutor="' + d.usuario_ejecutor + '" ><i class="fas fa-user-edit"></i></button><a> </a>';
                }
                if (d.usuario_ejecutor === usuarioActual) {
                    acciones += '<button class="accion-btn" title="Finalizar Tarea" id="' + d.id_relacion + '" name="firmar_documento" onclick="botones(this.id, this.name, \'tareas\')"><i class="fas fa-check"></i></button>';
                }
            }
            
            // Si la tarea está finalizada, muestra acciones diferentes o ninguna acción
            if (d.estado === 'Finalizada') {
                acciones += 'No hay acciones disponibles para tareas finalizadas.';
            }

            acciones += '</td></tr></table>';
            return acciones;
        }
        }
        function filtrar_listado_estado(estado) {
        var table = $('#listado').DataTable();
        table.column(2).search(estado).draw(); // Asumiendo que la columna 1 es la de
        if (estado==""){
            table.column(5).search("").draw();
        }
    }
    function filtrar_listado_usuario() {
        var table = $('#listado').DataTable();
        table.column(5).search(usuarioActual).draw(); // Asumiendo que la columna 1 es la de
    }
    $(document).on('click', 'button[name="cambiar_usuario"]', function() {
        var tareaId = $(this).attr('id');
        var usuarioEjecutorOriginal = $(this).data('usuario_ejecutor');
        ejecutorOriginal
        $('#ejecutorOriginal').val(usuarioEjecutorOriginal);
        $('#idTarea').val(tareaId);
        $('#modalCambiarUsuario').show();
    });

    // Cerrar el modal
    $('.close').click(function() {
        $('#modalCambiarUsuario').hide();
    });

    // Manejar el envío del formulario
    $('#formCambiarUsuario').on('submit', function(e) {
        e.preventDefault();
        var datosFormulario = $(this).serialize();
        // Obtener los valores del formulario
        var usuarioNuevo = document.getElementById('usuarioNuevo').value;
        var usuarioOriginal = document.getElementById('ejecutorOriginal').value;

        // Validar que el usuario nuevo no sea null y sea diferente del original
        if (!usuarioNuevo || usuarioNuevo === usuarioOriginal) {
            alert("Debes seleccionar un usuario diferente del actual.");
            return;
        }

        // Preguntar al usuario si está seguro
        var confirmacion = confirm("¿Estás seguro de que deseas realizar este cambio?");
        if (confirmacion) {
            // Si el usuario confirma, enviar el formulario
            $.ajax({
            url: './backend/tareas/tareasBE.php', // Asegúrate de ajustar esta URL
            type: 'POST',
            data: datosFormulario,
            success: function(response) {
                // Aquí puedes manejar la respuesta
                alert('Usuario Cambiado');
                $('#modalCambiarUsuario').hide();
                // Aquí deberías también recargar o actualizar tu tabla de tareas
                $("#notificaciones").click();
            },
            error: function() {
                alert('Error al cambiar el usuario');
            }
        });
        }
    });