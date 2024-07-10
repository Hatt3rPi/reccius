<style>
    #calendar {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 10px;
    }
</style>


<div id="calendar" class="calendar"></div>

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
            }
        });

        calendar.render();
    });
</script>