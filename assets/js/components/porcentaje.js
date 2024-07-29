(function() {
    fetch('../pages/backend/components/porcentaje.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const ctx = document.getElementById('clas6_pieChart').getContext('2d');
                const pieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Aprobados', 'Rechazados'],
                        datasets: [{
                            label: 'Calidad de Productos',
                            data: [data.approvedPercentage, data.rejectedPercentage],
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(255, 99, 132, 0.2)'
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
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
                                            label += context.parsed + '%';
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
})();
