document.getElementById('getWeatherBtn').addEventListener('click', getWeather);

function getWeather() {
    const city = document.getElementById('city').value;
    const apiKey = '0feabf126fec2094c8ad50be035553ef';
    const apiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`;

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.cod === 200) {
                const weatherEmojis = {
                    "clear sky": "☀️",
                    "few clouds": "🌤️",
                    "scattered clouds": "🌥️",
                    "broken clouds": "☁️",
                    "overcast clouds": "☁️",
                    "light rain": "🌦️",
                    "moderate rain": "🌧️",
                    "heavy intensity rain": "🌧️",
                    "very heavy rain": "🌧️",
                    "extreme rain": "🌧️",
                    "freezing rain": "🌧️❄️",
                    "light intensity shower rain": "🌦️",
                    "shower rain": "🌧️",
                    "heavy intensity shower rain": "🌧️",
                    "ragged shower rain": "🌧️",
                    "light snow": "🌨️",
                    "snow": "🌨️",
                    "heavy snow": "❄️",
                    "sleet": "🌨️",
                    "light shower sleet": "🌨️",
                    "shower sleet": "🌨️",
                    "light rain and snow": "🌨️🌧️",
                    "rain and snow": "🌨️🌧️",
                    "light shower snow": "🌨️",
                    "shower snow": "🌨️",
                    "heavy shower snow": "❄️",
                    "mist": "🌫️",
                    "smoke": "🌫️",
                    "haze": "🌫️",
                    "sand/dust whirls": "🌫️",
                    "fog": "🌫️",
                    "sand": "🌫️",
                    "dust": "🌫️",
                    "volcanic ash": "🌋",
                    "squalls": "🌬️",
                    "tornado": "🌪️",
                    "drizzle": "🌦️",
                    "light intensity drizzle": "🌦️",
                    "heavy intensity drizzle": "🌧️",
                    "light intensity drizzle rain": "🌦️",
                    "drizzle rain": "🌦️",
                    "heavy intensity drizzle rain": "🌧️",
                    "shower rain and drizzle": "🌧️",
                    "heavy shower rain and drizzle": "🌧️",
                    "shower drizzle": "🌦️"
                };

                const description = data.weather[0].description;
                const emoji = weatherEmojis[description.toLowerCase()] || "❓";

                document.getElementById('temp').innerText = `${data.main.temp} °C`;
                document.getElementById('humidity').innerText = `${data.main.humidity} %`;
                document.getElementById('description').innerText = emoji;
                document.getElementById('wind').innerText = `${data.wind.speed} m/s`;
            } else {
                alert('Ciudad no encontrada');
            }
        })
        .catch(error => {
            console.error('Error al obtener el clima:', error);
            alert('Error al obtener el clima');
        });
}
