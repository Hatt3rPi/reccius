<link rel="stylesheet" href="../assets/css/components/Index_components/Component_Calendario.css">


<div class="calendar-container">
    <h2>Personal Calendar</h2>
    <div class="calendar-header">
        <span class="month-change" id="prev-month">&#9664;</span>
        <span id="current-month-year"></span>
        <span class="month-change" id="next-month">&#9654;</span>
    </div>
    <div class="calendar-body">
        <div class="calendar-weekdays">
            <div>Sun</div>
            <div>Mon</div>
            <div>Tue</div>
            <div>Wed</div>
            <div>Thu</div>
            <div>Fri</div>
            <div>Sat</div>
        </div>
        <div class="calendar-days" id="calendar-days"></div>
    </div>
    <div class="calendar-footer">
        <div><span class="legend holiday"></span> Govt Holiday</div>
        <div><span class="legend leave"></span> Leave</div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarDays = document.getElementById('calendar-days');
        const currentMonthYear = document.getElementById('current-month-year');
        const prevMonth = document.getElementById('prev-month');
        const nextMonth = document.getElementById('next-month');
        const today = new Date();
        let currentMonth = today.getMonth();
        let currentYear = today.getFullYear();

        const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        function renderCalendar(month, year) {
            calendarDays.innerHTML = '';
            currentMonthYear.textContent = `${months[month]} ${year}`;
            const firstDay = (new Date(year, month)).getDay();
            const daysInMonth = 32 - new Date(year, month, 32).getDate();

            console.log(`Rendering calendar for ${months[month]} ${year}`);
            console.log(`Days in month: ${daysInMonth}, First day index: ${firstDay}`);

            for (let i = 0; i < firstDay; i++) {
                calendarDays.innerHTML += '<div class="empty"></div>';
            }

            for (let i = 1; i <= daysInMonth; i++) {
                const day = document.createElement('div');
                day.textContent = i;
                if (i === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
                    day.classList.add('today');
                }
                // Example of adding holiday or leave class
                if (i % 7 === 0) { // Mock condition for holidays
                    day.classList.add('holiday');
                } else if (i % 5 === 0) { // Mock condition for leave
                    day.classList.add('leave');
                }
                calendarDays.appendChild(day);
            }
        }

        prevMonth.addEventListener('click', () => {
            currentMonth = (currentMonth === 0) ? 11 : currentMonth - 1;
            currentYear = (currentMonth === 11) ? currentYear - 1 : currentYear;
            renderCalendar(currentMonth, currentYear);
        });

        nextMonth.addEventListener('click', () => {
            currentMonth = (currentMonth === 11) ? 0 : currentMonth + 1;
            currentYear = (currentMonth === 0) ? currentYear + 1 : currentYear;
            renderCalendar(currentMonth, currentYear);
        });

        renderCalendar(currentMonth, currentYear);
    });
</script>