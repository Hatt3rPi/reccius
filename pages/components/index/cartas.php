<link rel="stylesheet" href="../assets/css/components/Index_components/Component_Cartas.css">

<div class="cartas-container">
    <div class="card card-custom card-blue">
        <div class="card-body">
            <h5 class="card-title" id="prod_liberados">0</h5>
            <p class="card-text">LIBERADOS</p>
        </div>
    </div>

    <div class="card card-custom card-green">
        <div class="card-body">
            <h5 class="card-title" id="prod_cuarentena">0</h5>
            <p class="card-text">EN CUARENTENA</p>
        </div>
    </div>

    <div class="card card-custom card-orange">
        <div class="card-body">
            <h5 class="card-title" id="prod_rechazados">0</h5>
            <p class="card-text">RECHAZADOS</p>
        </div>
    </div>

    <div class="card card-custom card-red">
        <div class="card-body">
            <h5 class="card-title" >0</h5>
            <p class="card-text">PRÓXIMOS VENCIMIENTOS<br>(&lt;30 días)</p>
        </div>
    </div>
</div>

<script>
    fetch('../pages/backend/components/productos_analisados.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.data.forEach(item => {
                    switch (item.estado.toLowerCase()) {
                        case 'liberado':
                            document.getElementById('prod_liberados').innerText = item.contador;
                            break;
                        case 'en cuarentena':
                            document.getElementById('prod_cuarentena').innerText = item.contador;
                            break;
                        case 'rechazado':
                            document.getElementById('prod_rechazados').innerText = item.contador;
                            break;
                        default:
                            console.error('Estado no reconocido:', item.estado);
                    }
                });
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
</script>
