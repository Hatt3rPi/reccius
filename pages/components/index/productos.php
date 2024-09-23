<div class="grafo2-container">
    <canvas id="barChart"></canvas>
</div>

<script>
    // Función para actualizar el gráfico
    function updateChart(data) {
        var ctx = document.getElementById('barChart').getContext('2d');
        var labels = data.map(item => item.tipo_producto); // Extrae los nombres de los tipos de productos
        var counts = data.map(item => item.contador); // Extrae los conteos de productos

        var pieChart = new Chart(ctx, {
            type: 'pie', // Gráfico de torta
            data: {
                labels: labels,
                datasets: [{
                    label: '# de Productos',
                    data: counts,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(255, 206, 150, 0.6)',
                        'rgba(54, 162, 135, 0.6)',
                        'rgba(75, 192, 222, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.raw !== null) {
                                    label += context.raw;
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // Solicitar datos al backend
    fetch('../pages/backend/components/productos.php')  // Reemplaza con la ruta correcta a tu backend
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Llamar a la función para actualizar el gráfico con los datos obtenidos
                updateChart(data.data);
            } else {
                console.error('Error en la respuesta del servidor:', data.message);
            }
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error);
        });
</script>
