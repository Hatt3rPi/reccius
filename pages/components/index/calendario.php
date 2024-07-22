<link rel="stylesheet" href="../assets/css/components/Index_components/Component_Calendario.css">

<body>
    <!-- Otros contenidos -->
    <div class="calendar-container">
        <div class="calendar-header">
            <button id="prevMonth">Prev</button>
            <h2 id="monthYear"></h2>
            <button id="nextMonth">Next</button>
        </div>
        <table id="calendar">
            <thead>
                <tr>
                    <th>Sun</th>
                    <th>Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                    <th>Sat</th>
                </tr>
            </thead>
            <tbody id="calendarBody">
                <!-- Calendar days will be generated by JavaScript -->
            </tbody>
        </table>
    </div>
    <script src="../assets/js/components/calendario.js"></script>
</body>