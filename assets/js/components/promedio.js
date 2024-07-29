(function() {
    function updatePromedio(filter) {
        let url = '../pages/backend/components/promedio.php';
        if (filter === 'month') {
            url += '?filter=month';
        } else if (filter === 'week') {
            url += '?filter=week';
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('promedio_value').innerText = data.promedioDias;
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    document.querySelectorAll('.filter-button').forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');
            updatePromedio(filter);
        });
    });

    // Inicialmente mostrar el tiempo promedio de todos los productos analizados
    updatePromedio('all');
})();
