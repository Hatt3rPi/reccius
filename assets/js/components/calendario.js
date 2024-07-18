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
        },
        events: [
            {
                title: 'Event 1',
                start: '2024-07-17',
                end: '2024-07-19'
            },
            {
                title: 'Event 2',
                start: '2024-07-23',
                allDay: true
            },
            {
                title: 'Event 3',
                start: '2024-07-30T14:30:00'
            }
        ]
    });

    calendar.render();
});
