<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Calendar</title>
    <style>
        .calendar-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        #calendar {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }

        #calendar thead th {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px;
            text-align: center;
            border: 1px solid #dee2e6;
        }

        #calendar tbody td {
            width: 14.28%;
            height: 100px;
            text-align: center;
            vertical-align: top;
            border: 1px solid #dee2e6;
            background-color: #ffffff;
        }

        #calendar tbody td:hover {
            background-color: #f1f1f1;
        }

        #calendar tbody td.fc-day-today {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
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
                <!-- Calendar days will be generated by JavaScript -->
            </tbody>
        </table>
    </div>
    <script>
        console.log("JavaScript is running!");

        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        const daysInMonth = (month, year) => new Date(year, month + 1, 0).getDate();
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();

        document.addEventListener('DOMContentLoaded', () => {
            console.log("DOM fully loaded and parsed");
            renderCalendar(currentMonth, currentYear);

            const prevButton = document.getElementById('prevMonth');
            const nextButton = document.getElementById('nextMonth');

            console.log("Adding event listeners to buttons");
            prevButton.addEventListener('click', () => {
                console.log("Prev button clicked");
                changeMonth(-1);
            });
            nextButton.addEventListener('click', () => {
                console.log("Next button clicked");
                changeMonth(1);
            });
        });

        function renderCalendar(month, year) {
            const monthYear = document.getElementById('monthYear');
            monthYear.textContent = `${monthNames[month]} ${year}`;

            const calendarBody = document.getElementById('calendarBody');
            calendarBody.innerHTML = '';

            const firstDay = new Date(year, month).getDay();
            const totalDays = daysInMonth(month, year);

            console.log(`Rendering calendar for ${monthNames[month]} ${year}`);
            console.log(`First day of the month is on: ${firstDay}`);
            console.log(`Total days in the month: ${totalDays}`);

            let date = 1;
            for (let i = 0; i < 6; i++) {
                const row = document.createElement('tr');
                console.log(`Creating row ${i}`);

                for (let j = 0; j < 7; j++) {
                    const cell = document.createElement('td');
                    if (i === 0 && j < firstDay) {
                        cell.textContent = '';
                        console.log(`Row ${i} Col ${j}: Empty`);
                    } else if (date > totalDays) {
                        cell.textContent = '';
                        console.log(`Row ${i} Col ${j}: Empty`);
                    } else {
                        cell.textContent = date;
                        console.log(`Row ${i} Col ${j}: ${date}`);
                        date++;
                    }
                    row.appendChild(cell);
                }

                calendarBody.appendChild(row);
                console.log(`Row ${i} added to calendar`);
            }
        }

        function changeMonth(step) {
            currentMonth += step;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            } else if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            console.log(`Changing month to: ${currentMonth}, year to: ${currentYear}`);
            renderCalendar(currentMonth, currentYear);
        }
    </script>
</body>
</html>
