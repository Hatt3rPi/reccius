document.getElementById('getWeatherBtn').addEventListener('click', getWeather);

function getWeather() {
    const city = document.getElementById('city').value;
    const apiKey = '0feabf126fec2094c8ad50be035553ef';
    const apiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`;

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.cod === 200) {
                const weatherData = `
                    <h2>Clima en ${data.name}</h2>
                    <p>Temperatura: ${data.main.temp} °C</p>
                    <p>Humedad: ${data.main.humidity}%</p>
                    <p>Descripción: ${data.weather[0].description}</p>
                `;
                document.getElementById('weatherResult').innerHTML = weatherData;
            } else {
                document.getElementById('weatherResult').innerHTML = `<p>Ciudad no encontrada</p>`;
            }
        })
        .catch(error => {
            console.error('Error al obtener el clima:', error);
            document.getElementById('weatherResult').innerHTML = `<p>Error al obtener el clima</p>`;
        });
}
