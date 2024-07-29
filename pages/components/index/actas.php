<link rel="stylesheet" href="../assets/css/components/Index_components/Component_Especs.css">
<div class="clas1-container">
    <canvas id="barChartActaMuestreo"></canvas>
</div>

<script>
    (function() {
    function initChart() {
        fetch('../pages/backend/components/acta.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const labels = data.estados.map(item => item.estado);
                    const counts = data.estados.map(item => item.contador);
                    const colors = [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ];
                    const borderColors = [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ];

                    var ctx = document.getElementById('pieChartActaMuestreo').getContext('2d');
                    var pieChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Número de Estados',
                                data: counts,
                                backgroundColor: colors.slice(0, counts.length),
                                borderColor: borderColors.slice(0, counts.length),
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
                                            if (context.parsed !== null) {
                                                label += context.parsed;
                                            }
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Ejecutar initChart solo cuando el canvas esté disponible
    if (document.readyState === 'complete') {
        initChart();
    } else {
        window.addEventListener('load', initChart);
    }
})();
</script>
