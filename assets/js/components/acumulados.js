(function() {
    fetch('../pages/backend/components/acumulados.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('clas5_value').innerText = data.totalProductosAnalizados;
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
})();
