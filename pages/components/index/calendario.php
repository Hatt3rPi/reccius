<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Calendar</title>
    <link rel="stylesheet" href="../assets/css/components/Index_components/Component_Calendario.css">
</head>
<body>
    <div class="calendar-container">
        <div class="calendar-header">
            <button id="prevMonth">Prev</button>
            <h2 id="monthYear">Month Year</h2>
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
                <!-- 6 rows of 7 cells each (total 42 cells) -->
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
                <tr>
                    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="../assets/js/components/calendario.js"></script>
</body>
</html>
