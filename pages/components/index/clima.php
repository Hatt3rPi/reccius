<link rel="stylesheet" href="../assets/css/components/Index_components/Component_Clima.css">
<!-- components/index/clima.php -->
<div class="attendance-component">
    <div class="weather">
        <span class="weather-icon">☁️</span>
        <span class="weather-date">21 September 2022</span>
    </div>
    <div class="attendance-stats">
        <div class="stat">
            <span class="stat-number" id="temp">70</span>
            <span class="stat-label">Temperatura (°C)</span>
        </div>
        <div class="stat">
            <span class="stat-number" id="humidity">20</span>
            <span class="stat-label">Humedad (%)</span>
        </div>
        <div class="stat">
            <span class="stat-number" id="description">7</span>
            <span class="stat-label">Descripción</span>
        </div>
        <div class="stat">
            <span class="stat-number" id="wind">3</span>
            <span class="stat-label">Viento (m/s)</span>
        </div>
    </div>
    <div>
        <label for="city">Ciudad:</label>
        <input type="text" id="city" value="Santiago">
        <button id="getWeatherBtn">Obtener Clima</button>
    </div>
</div>
<script src="../assets/js/components/clima.js"></script>
