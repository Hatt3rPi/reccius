<!DOCTYPE html>
<!-- pages/index_administrador.php -->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Productos</title>
    <link rel="stylesheet" href="../assets/css/index_administrador.css">
    <link rel="stylesheet" href="../assets/css/components/Index_components/Component_Tarea.css">
</head>

<body>
    <div class="container dashboard">
        <div class="row">
            <div class="col-md-12 mt-5">
                <h2 class="section-title">DISPONIBILIDAD DE PRODUCTOS</h2>
                <div class="row backgroundrow">
                    <div class="col-md-3">
                        <div class="card card-custom card-blue">
                            <div class="card-body">
                                <h5 class="card-title" id="prod_liberados" name="prod_liberados">92.31M</h5>
                                <p class="card-text">LIBERADOS</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-custom card-green">
                            <div class="card-body">
                                <h5 class="card-title" id="prod_enCurso" name="prod_enCurso">79.30M</h5>
                                <p class="card-text">EN CURSO</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-custom card-orange">
                            <div class="card-body">
                                <h5 class="card-title" id="prod_rechazados" name="prod_rechazados">13.02M</h5>
                                <p class="card-text">RECHAZADOS</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-custom card-red">
                            <div class="card-body">
                                <h5 class="card-title" id="prod_vencimientos" name="prod_vencimientos">7.20M</h5>
                                <p class="card-text">PRÓXIMOS VENCIMIENTOS<br>(&lt;30 días)</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tareas-component">
                    <?php include 'components/index/tareas.php'; ?>
                </div>
                <div id="calendario-component">
                    <?php include 'components/index/calendario.php'; ?>
                </div>

                <div class="row mt-4 backgroundrow">
                    <div class="col-md-12">
                        <h5 class="chart-title">Productos en Cuarentena</h5>
                        <div class="chart-container">
                            <canvas id="productosCuarentenaChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 backgroundrow">
                    <div class="col-md-12">
                        <h5 class="chart-title">Evolución de productos</h5>
                        <div class="chart-container">
                            <canvas id="evolucionProductosChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 backgroundrow">
                    <div class="col-md-12">
                        <h5 class="chart-title">Evolución tiempo en cuarentena</h5>
                        <div class="chart-container">
                            <canvas id="evolucionCuarentenaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <br><br><br>
            </div>
            <div class="col-md-12">
                <h2 class="section-title">ESPECIFICACIONES DE PRODUCTOS</h2>
                <div class="row backgroundrow">
                    <div class="col-md-4">
                        <div class="card card-custom card-blue">
                            <div class="card-body">
                                <h5 class="card-title" id="esp_vigentes" name="esp_vigentes">92.31M</h5>
                                <p class="card-text">VIGENTES</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-custom card-green">
                            <div class="card-body">
                                <h5 class="card-title" id="esp_enCurso" name="esp_enCurso">79.30M</h5>
                                <p class="card-text">EN CURSO</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-custom card-orange">
                            <div class="card-body">
                                <h5 class="card-title" id="esp_vencimientos" name="esp_vencimientos">13.02M</h5>
                                <p class="card-text">PRÓXIMOS VENCIMIENTOS<br>(&lt;30 días)</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 backgroundrow">
                    <div class="col-md-6">
                        <h5 class="chart-title">Tipos de Productos</h5>
                        <div class="chart-container">
                            <canvas id="tipoProductosChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="chart-title">Estado especificaciones de productos</h5>
                        <div class="chart-container">
                            <canvas id="estadoEspecificacionesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function actualizarGraficos(datos) {
            const liberados = datos.filter(d => d.estado === 'liberado').length;
            const enCurso = datos.filter(d => d.estado === 'En curso').length;
            const rechazados = datos.filter(d => d.estado === 'rechazado').length;
            const proximosVencimientos = datos.filter(d => {
                const fechaVencimiento = new Date(d.fecha_vencimiento);
                const hoy = new Date();
                const diferencia = (fechaVencimiento - hoy) / (1000 * 60 * 60 * 24);
                return diferencia < 30;
            }).length;

            $('#prod_liberados').text(liberados);
            $('#prod_enCurso').text(enCurso);
            $('#prod_rechazados').text(rechazados);
            $('#prod_vencimientos').text(proximosVencimientos);

            const productosCuarentena = datos
                .filter(d => d.estado === 'En cuarentena')
                .sort((a, b) => b.dias_en_cuarentena - a.dias_en_cuarentena);

            const etiquetasCuarentena = productosCuarentena.map(d => `${d.producto} - lote[${d.lote}]`);
            const diasCuarentena = productosCuarentena.map(d => d.dias_en_cuarentena);

            var ctxProductosCuarentena = document.getElementById('productosCuarentenaChart').getContext('2d');
            var productosCuarentenaChart = new Chart(ctxProductosCuarentena, {
                type: 'bar',
                data: {
                    labels: etiquetasCuarentena,
                    datasets: [{
                        label: 'Días en Cuarentena',
                        data: diasCuarentena,
                        backgroundColor: '#FFCE56'
                    }]
                },
                options: {
                    responsive: true,
                    indexAxis: 'y',
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Productos'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Días en Cuarentena'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });

            // Datos para el gráfico de evolución de productos
            const productosLiberados = datos.filter(d => d.estado === 'liberado');
            const productosRechazados = datos.filter(d => d.estado === 'rechazado');

            const mesesLiberados = productosLiberados.reduce((acc, cur) => {
                const mes = cur.mes_salida_cuarentena || new Date().toISOString().slice(0, 7);
                acc[mes] = (acc[mes] || 0) + 1;
                return acc;
            }, {});

            const mesesRechazados = productosRechazados.reduce((acc, cur) => {
                const mes = cur.mes_salida_cuarentena || new Date().toISOString().slice(0, 7);
                acc[mes] = (acc[mes] || 0) + 1;
                return acc;
            }, {});

            const meses = [...new Set([...Object.keys(mesesLiberados), ...Object.keys(mesesRechazados)])].sort();

            let acumuladoLiberados = 0;
            let acumuladoRechazados = 0;
            const dataLiberados = meses.map(mes => acumuladoLiberados += (mesesLiberados[mes] || 0));
            const dataRechazados = meses.map(mes => acumuladoRechazados += (mesesRechazados[mes] || 0));

            var ctxEvolucionProductos = document.getElementById('evolucionProductosChart').getContext('2d');
            var evolucionProductosChart = new Chart(ctxEvolucionProductos, {
                type: 'line',
                data: {
                    labels: meses,
                    datasets: [{
                            label: 'Liberados (Acumulado)',
                            data: dataLiberados,
                            borderColor: '#36A2EB',
                            fill: false
                        },
                        {
                            label: 'Rechazados (Acumulado)',
                            data: dataRechazados,
                            borderColor: '#FF6384',
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Datos para el gráfico de evolución de cuarentena
            const productosFueraCuarentena = datos.filter(d => d.estado !== 'En cuarentena');

            const diasCuarentenaPorMes = productosFueraCuarentena.reduce((acc, cur) => {
                const mes = cur.mes_salida_cuarentena || new Date().toISOString().slice(0, 7);
                acc[mes] = (acc[mes] || []).concat(cur.dias_en_cuarentena);
                return acc;
            }, {});

            const mesesCuarentena = Object.keys(diasCuarentenaPorMes).sort();

            const dataCuarentena = mesesCuarentena.map(mes => {
                const dias = diasCuarentenaPorMes[mes];
                return dias.reduce((sum, dia) => sum + dia, 0) / dias.length; // Promedio de días en cuarentena
            });

            var ctxEvolucionCuarentena = document.getElementById('evolucionCuarentenaChart').getContext('2d');
            var evolucionCuarentenasChart = new Chart(ctxEvolucionCuarentena, {
                type: 'line',
                data: {
                    labels: mesesCuarentena,
                    datasets: [{
                        label: 'Promedio de Días en Cuarentena',
                        data: dataCuarentena,
                        borderColor: '#FF6384',
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
        $(document).ready(function() {


            var ctxTipoProductos = document.getElementById('tipoProductosChart').getContext('2d');
            var tipoProductosChart = new Chart(ctxTipoProductos, {
                type: 'doughnut',
                data: {
                    labels: ['Producto 1', 'Producto 2', 'Producto 3', 'Producto 4'],
                    datasets: [{
                        data: [10, 20, 30, 40],
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4CAF50']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            var ctxEstadoEspecificaciones = document.getElementById('estadoEspecificacionesChart').getContext('2d');
            var estadoEspecificacionesChart = new Chart(ctxEstadoEspecificaciones, {
                type: 'bar',
                data: {
                    labels: ['Estado 1', 'Estado 2', 'Estado 3', 'Estado 4'],
                    datasets: [{
                        label: 'Especificaciones',
                        data: [12, 19, 3, 5],
                        backgroundColor: '#36A2EB'
                    }]
                },
                options: {
                    responsive: true,
                    indexAxis: 'y',
                    maintainAspectRatio: false
                }
            });

            $.ajax({
                url: 'backend/index/index_administrador.php',
                method: 'GET',
                success: function(response) {
                    const datos = response.data;
                    actualizarGraficos(datos);
                },
                error: function(error) {
                    console.error('Error al obtener los datos:', error);
                }
            });

        });
    </script>
</body>

</html>