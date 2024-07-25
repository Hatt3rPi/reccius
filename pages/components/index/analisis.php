<link rel="stylesheet" href="../assets/css/components/Index_components/Component_Especs.css">
<div class="clas1-container">
    <canvas id="barChartClas2"></canvas>
</div>
<script>
    var ctx = document.getElementById('barChartClas2').getContext('2d');
    var barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Categoria 1', 'Categoria 2', 'Categoria 3', 'Categoria 4'],
            datasets: [{
                label: 'Número de Categorías',
                data: [12, 19, 3, 5],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
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
</script>
