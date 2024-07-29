(function() {
    fetch('../pages/backend/components/porcentaje.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('approved_percentage').innerText = data.approvedPercentage + '%';
                document.getElementById('rejected_percentage').innerText = data.rejectedPercentage + '%';
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
})();
