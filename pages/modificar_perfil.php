<?php
session_start();

// Verificar si la variable de sesión "usuario" no está establecida o está vacía.
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    // Redirigir al usuario a la página de inicio de sesión.
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Modificar Perfil</title>
    <!-- Añade aquí tus estilos o referencias a CSS -->
    <link rel="stylesheet" href="../assets/css/ModificacionPerfil.css">
    <link rel="stylesheet" href="../assets/css/Notificacion.css">
    <script src="../assets/js/notify.js"></script>
    <script src="../assets/js/images.js"></script>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2>Modificar Perfil</h2>
            <form id="formPerfil" enctype="multipart/form-data">

                <!-- Sección de Cambio de Contraseña -->
                <div class="seccion seccion-deshabilitada">

                    <h3>Cambio de Contraseña</h3>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="switch_contrasena" onclick="toggleInputs('switch_contrasena')">
                        <label class="form-check-label" for="switch_contrasena">Editar</label>
                        <input type="hidden" id="editarContrasena" name="editarContrasena" value="0">
                    </div>
                    <br>
                    <div>
                        <label for="passwordActual">Contraseña Actual:</label>
                        <input class="switch_contrasena" type="password" id="passwordActual" name="passwordActual" disabled>
                    </div>
                    <div>
                        <label for="nuevaPassword">Nueva Contraseña:</label>
                        <input class="switch_contrasena" type="password" id="nuevaPassword" name="nuevaPassword" disabled>
                    </div>
                    <div>
                        <label for="confirmarPassword">Confirmar Nueva Contraseña:</label>
                        <input class="switch_contrasena" type="password" id="confirmarPassword" name="confirmarPassword" disabled>
                    </div>

                </div>
                <div class="seccion seccion-deshabilitada">
                    <h3>Cambio de Foto de Perfil</h3>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="switch_foto" onclick="toggleInputs('switch_foto')">
                        <label class="form-check-label" for="switch_foto">Editar</label>
                        <input type="hidden" id="editarFoto" name="editarFoto" value="0">
                    </div>
                    <br>
                    <div>
                        <label for="fotoPerfil">Foto de Perfil:</label>
                        <input class="switch_foto" type="file" id="fotoPerfil" name="fotoPerfil" accept="image/*" disabled onchange="handleImageUploadPerfil(event)">
                        <div id="fotoPerfilPreview" class="d-flex justify-content-center">
                            <!-- Aquí se mostrará el enlace al archivo existente -->
                        </div>
                        <button type="button" id="cancelFotoPerfil" style="display: none;">Eliminar foto</button>
                    </div>
                </div>
                <div class="seccion seccion-deshabilitada">
                    <h3>Editar información de Usuario</h3>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="switch_info" onclick="toggleInputs('switch_info')">
                        <label class="form-check-label" for="switch_info">Editar</label>
                        <input type="hidden" id="editarInfo" name="editarInfo" value="0">
                    </div>
                    <br>
                    <div>
                        <label for="cargo">Cargo:</label>
                        <input class="switch_info" type="text" id="cargo" name="cargo" disabled>
                    </div>
                    <div>
                        <label for="nombre">Nombre:</label>
                        <input class="switch_info" type="text" id="nombre" name="nombre" disabled>
                    </div>
                    <div>
                        <label for="nombre_corto">Nombre y Cargo Abreviado:</label>
                        <input class="switch_info" type="text" id="nombre_corto" name="nombre_corto" disabled>
                    </div>
                </div>
                <div class="seccion seccion-deshabilitada">

                    <h3>Certificado Nacional de Prestadores Individuales de Salud</h3>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="switch_certificado" onclick="toggleInputs('switch_certificado')">
                        <label class="form-check-label" for="switch_certificado">Editar</label>
                        <input type="hidden" id="editarCertificado" name="editarCertificado" value="0">
                    </div>
                    <br>
                    <div>
                        <label for="certificado">Cargar Documento (extraído desde https://rnpi.superdesalud.gob.cl/):</label>
                        <input class="switch_certificado" type="file" id="certificado" name="certificado" accept="application/pdf" disabled>
                        <div id="certificadoExistente">
                            <!-- Aquí se mostrará el enlace al archivo existente -->
                        </div>
                    </div>
                </div>
                <div class="seccion seccion-deshabilitada">

                    <h3>Firma</h3>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="switch_firma" onclick="toggleInputs('switch_firma')">
                        <label class="form-check-label" for="switch_firma">Editar</label>
                        <input type="hidden" id="editarfirma" name="editarfirma" value="0">
                    </div>
                    <br>
                    <div>
                        <label for="firma">Imagen de Firma:</label>
                        <input class="switch_firma" type="file" id="firma" name="firma" accept="image/*" disabled>
                        <div id="firmaExistente">
                            <!-- Aquí se mostrará el enlace al archivo existente -->
                        </div>
                    </div>
                </div>
                <canvas style="display: none;"></canvas>
                <input type="hidden" name="usuario" value="<?php echo $_SESSION['usuario']; ?>">
                <button type="button" name="modificarPerfil" onclick="guardar()">Modificar Perfil</button>
            </form>
        </div>
    </div>


    <script>
        function cargarInformacionExistente() {
            $.ajax({
                url: './backend/usuario/modifica_perfilFETCH.php',
                type: 'GET',
                success: function(response) {
                    var usuario = response.usuario;
                    $('#nombre').val(usuario.nombre);
                    $('#nombre_corto').val(usuario.nombre_corto);
                    $('#cargo').val(usuario.cargo);
                    $('#nombre').val(usuario.nombre);
                    if (usuario.foto_perfil) {
                        document.getElementById('fotoPerfilPreview').innerHTML = '<img src="' + usuario.foto_perfil + '" alt="Foto de perfil" />';
                    }
                    if (usuario.foto_firma) {
                        document.getElementById('firmaExistente').innerHTML = '<img src="' + usuario.foto_firma + '" alt="Foto de perfil" />';
                    }
                    
                    if (usuario.certificado) {
                        document.getElementById('certificadoExistente').innerHTML = '<a href="https://customware.cl/reccius/documentos_publicos/' + usuario.certificado + '" target="_blank">Ver Certificado</a>';
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud: ", status, error);
                }
            });
        }

        document.getElementById('formPerfil').addEventListener('submit', function(event) {
            var password = document.getElementById('nuevaPassword').value;
            var confirmPassword = document.getElementById('confirmarPassword').value;

            // Validar que las contraseñas coincidan
            if (password !== confirmPassword) {
                alert('Las contraseñas no coinciden.');
                event.preventDefault(); // Prevenir el envío del formulario
                return false;
            }

            // Validación adicional aquí si es necesario
        });

        //edit foto de perfil
        var fotoPerfilPreview = $('#fotoPerfilPreview');
        var fotoPerfilCancel = $('#cancelFotoPerfil');
        var blobImgPerfil = null;

        function handleImageUploadPerfil(e) {
            if (e.target.files && e.target.files[0]) {
                var canvas = document.querySelector("canvas");
                processImageSquare(e.target.files[0], canvas, function(error, result) {
                    if (error) {
                        console.error(error);
                        return;
                    }
                    fotoPerfilCancel.show();
                    blobImgPerfil = result.blob;
                    fotoPerfilPreview.html('<img class="img-thumbnail" src="' + result.dataURL + '" alt="Foto de perfil" />');
                    console.log({
                        fotoPerfilCancel,
                        blobImgPerfil,
                        fotoPerfilPreview
                    })
                });
            }
        }
        fotoPerfilCancel.on('click', function(e) {
            e.preventDefault();
            blobImgPerfil = null;
            fotoPerfilPreview.empty();
            fotoPerfilCancel.hide();
            console.log({
                fotoPerfilCancel,
                blobImgPerfil,
                fotoPerfilPreview
            })
        })

        // edit switches certificado
        function toggleInputs(switchClass) {
            var inputs = document.querySelectorAll('.' + switchClass);
            // Determinar si los campos están deshabilitados o no
            var areDisabled = inputs.length > 0 ? inputs[0].disabled : false;
            // Encuentra la sección más cercana
            var seccion = inputs[0].closest('.seccion');
            // Alternar el estado de cada campo
            inputs.forEach(function(input) {
                input.disabled = !areDisabled;
            });
            // Alternar la clase de la sección y el valor del campo oculto
            if (areDisabled) {
                seccion.classList.remove('seccion-deshabilitada');
                // Actualiza el valor del campo oculto correspondiente
                if (switchClass === 'switch_contrasena') {
                    document.getElementById('editarContrasena').value = '1';
                } else if (switchClass === 'switch_foto') {
                    document.getElementById('editarFoto').value = '1';
                } else if (switchClass === 'switch_info') {
                    document.getElementById('editarInfo').value = '1';
                } else if (switchClass === 'switch_certificado') {
                    document.getElementById('editarCertificado').value = '1';
                } else if (switchClass === 'switch_firma') {
                    document.getElementById('editarfirma').value = '1';
                }
            } else {
                seccion.classList.add('seccion-deshabilitada');
                // Restablece el valor del campo oculto
                if (switchClass === 'switch_contrasena') {
                    document.getElementById('editarContrasena').value = '0';
                } else if (switchClass === 'switch_foto') {
                    document.getElementById('editarFoto').value = '0';
                } else if (switchClass === 'switch_info') {
                    document.getElementById('editarInfo').value = '0';
                } else if (switchClass === 'switch_certificado') {
                    document.getElementById('editarCertificado').value = '0';
                } else if (switchClass === 'switch_firma') {
                    document.getElementById('editarfirma').value = '0';
                }
            }
        }

        function guardar() {
            event.preventDefault();
            var formData = new FormData();

            if ($('#switch_contrasena').is(':checked')) {
                var passwordActual = $('#nuevaPassword').val();
                var nuevaPassword = $('#nuevaPassword').val();
                var confirmarPassword = $('#confirmarPassword').val();

                if (nuevaPassword !== confirmarPassword || nuevaPassword.length < 8) {
                    alert("Las contraseñas no coinciden o son muy cortas.");
                    return;
                }
                formData.append('passwordActual', passwordActual);
                formData.append('nuevaPassword', nuevaPassword);
            }

            if ($('#switch_foto').is(':checked')) {
                if (blobImgPerfil === null) {
                    alert("Por favor, selecciona una imagen.");
                    return;
                }
                formData.append('imagen', blobImgPerfil);
            }

            if ($('#switch_firma').is(':checked')) {
                var fotoFirma = $('#firma')[0].files[0];
                if (fotoFirma !== undefined && fotoFirma !== null) {
                    formData.append('firma', fotoFirma);
                } else {
                    alert("Por favor, selecciona una firma.");
                }
            }

            if ($('#switch_certificado').is(':checked')) {
                var certificado = $('#certificado')[0].files[0];
                if (certificado !== undefined && certificado !== null) {
                    formData.append('certificado', certificado);
                } else {
                    alert("Por favor, selecciona un certificado.");
                }
            }

            if ($('#switch_info').is(':checked')) {
                var cargo = $('#cargo').val();
                var nombre = $('#nombre').val();
                var nombre_corto = $('#nombre_corto').val();
                if (cargo === "" || nombre === "" || nombre_corto === "") {
                    alert("Por favor, rellena todos los campos.");
                    return;
                }
                formData.append('cargo', cargo);
                formData.append('nombre', nombre);
                formData.append('nombre_corto', nombre_corto);

            }

            $.ajax({
                url: './backend/usuario/modifica_perfilFETCH.php',
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    var res = JSON.parse(response);
                    console.log(res);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error en la solicitud: ', textStatus, errorThrown);
                }
            });

        };
    </script>

</body>

</html>