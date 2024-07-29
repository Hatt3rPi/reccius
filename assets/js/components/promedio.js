(function() {
    fetch('../pages/backend/components/promedio.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const labels = ['Promedio de días en cuarentena'];
                const promedioDias = data.data.promedioDias;
                const datasetInicial = [promedioDias];
                const datasetFinal = [promedioDias]; // Puedes cambiar esto si tienes otros datos para el tiempo final

                const chartData = {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Tiempo Inicial',
                            data: datasetInicial,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: false,
                            tension: 0.1
                        },
                        {
                            label: 'Tiempo Final',
                            data: datasetFinal,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            fill: false,
                            tension: 0.1
                        }
                    ]
                };

                const config = {
                    type: 'line',
                    data: chartData,
                    options: {
                        responsive: true,
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
                                            label += context.parsed.y + ' días';
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                };

                var ctx = document.getElementById('timeComparisonChart').getContext('2d');
                var timeComparisonChart = new Chart(ctx, config);
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
})();
