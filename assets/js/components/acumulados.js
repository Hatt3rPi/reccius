(function() {
    function updateCount(filter) {
        let url = '../pages/backend/components/acumulados.php';
        if (filter === 'month') {
            url += '?filter=month';
        } else if (filter === 'week') {
            url += '?filter=week';
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('clas5_value').innerText = data.totalProductosAnalizados;
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    document.querySelectorAll('.filter-button').forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');
            updateCount(filter);
        });
    });

    // Inicialmente mostrar todos los productos analizados acumulados
    updateCount('all');
})();
