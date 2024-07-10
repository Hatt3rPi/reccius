<link rel="stylesheet" href="../assets/css/components/Index_components/Component_Calendario.css">

<div class="calendar-container">
    <h2>Personal Calendar</h2>
    <div id="calendar"></div>
</div>


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