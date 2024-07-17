<link rel="stylesheet" href="../assets/css/components/Index_components/Component_Clima.css">
<!-- components/index/clima.php -->
<div class="attendance-component">
    <div class="weather">
        <span class="weather-icon">☁️</span>
        <span class="weather-date">21 September 2022</span>
    </div>
    <div class="attendance-stats">
        <div class="stat">
            <span class="stat-number">70</span>
            <span class="stat-label">Present-on time</span>
        </div>
        <div class="stat">
            <span class="stat-number">20</span>
            <span class="stat-label">Late</span>
        </div>
        <div class="stat">
            <span class="stat-number">7</span>
            <span class="stat-label">Absent</span>
        </div>
        <div class="stat">
            <span class="stat-number">3</span>
            <span class="stat-label">Leave</span>
        </div>
    </div>
    <div>
        <label for="city">Ciudad:</label>
        <input type="text" id="city" value="Santiago">
        <button id="getWeatherBtn">Obtener Clima</button>
    </div>
    <div id="weatherResult"></div>
</div>
<script src="../assets/js/components/clima.js"></script>

