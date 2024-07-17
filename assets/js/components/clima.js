document.getElementById('getWeatherBtn').addEventListener('click', getWeather);

function getWeather() {
    const city = document.getElementById('city').value;
    const apiKey = '0feabf126fec2094c8ad50be035553ef';
    const apiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`;

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.cod === 200) {
                document.getElementById('temp').innerText = data.main.temp;
                document.getElementById('humidity').innerText = data.main.humidity;
                document.getElementById('description').innerText = data.weather[0].description;
                document.getElementById('wind').innerText = data.wind.speed;
            } else {
                alert('Ciudad no encontrada');
            }
        })
        .catch(error => {
            console.error('Error al obtener el clima:', error);
            alert('Error al obtener el clima');
        });
}
