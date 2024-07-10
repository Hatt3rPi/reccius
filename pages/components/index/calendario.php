<link rel="stylesheet" href="../assets/css/components/Index_components/Component_Calendario.css">

<div class="calendar-container">
    <h2>Personal Calendar</h2>
    <div id="calendar"></div>
</div>

<!-- Calendario -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/locales-all.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            contentHeight: 'auto',
            dayMaxEvents: true,
            views: {
                dayGridMonth: {
                    titleFormat: {
                        year: 'numeric',
                        month: 'long'
                    }
                }
            }
        });

        calendar.render();
    });
</script>