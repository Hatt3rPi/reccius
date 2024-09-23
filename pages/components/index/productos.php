<div class="grafo2-container">
    <canvas id="barChart"></canvas>
</div>

<script>
    // Función para actualizar el gráfico
    function updateChart(data) {
        var ctx = document.getElementById('barChart').getContext('2d');
        var labels = data.map(item => item.estado); // Extrae los nombres de los estados
        var counts = data.map(item => item.contador); // Extrae los conteos de productos

        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '# de Productos',
                    data: counts,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
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
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y;
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
