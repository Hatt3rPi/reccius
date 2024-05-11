<?php

session_start();
require_once "/home/customw2/conexiones/config_reccius.php";

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}

$fecha = new DateTime();
$temp = $fecha->getTimestamp();
?>

<div class="modal" style="background-color: #00000080 !important;" id="modal_<?php echo $temp ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog  modal-dialog-centered modal-xl modal__dialog">
        <div id="add_contizacion_form_<?php echo $temp ?>" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <?php echo json_encode($_POST['title'] ?? 'Modal'); ?>
                </h5>
                <button type="button" class="close" id="add_modal_close_<?php echo $temp ?>" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo json_encode($_POST['body'] ?? '¿Estás seguro de realizar esta acción?'); ?>
            </div>
            <div class="alert alert-danger mx-3 text-center" style="display: none" role="alert" id="add_error_alert_<?php echo $temp ?>"></div>
            <div class="modal-footer">
                <button type="button" id="add_contizacion_form_button_<?php echo $temp ?>" class="btn btn-primary">
                    <?php echo json_encode($_POST['button_text'] ?? 'Aceptar'); ?>
                </button>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#add_modal_close_<?php echo $temp ?>').on('click', function(event) {
                $("#modal_<?php echo $temp ?>").remove();
            })
            $("#add_contizacion_form_button_<?php echo $temp ?>").on('click', function(event) {
                <?php echo json_encode($_POST['button_action'] ?? 'console.log("Aceptar")'); ?>
            })
        })
    </script>
</div>