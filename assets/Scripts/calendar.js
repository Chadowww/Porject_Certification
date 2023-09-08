if (document.getElementById('calendar')) {

    document.addEventListener('DOMContentLoaded', function() {
        let calendarEl = document.getElementById('calendar');
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'fr',
            timeZone: 'Europe/Paris',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay',
            },
            views: {
                timeGridWeek: {
                    type: 'timeGrid',
                    duration: {days: 7},
                    buttonText: 'Semaine',
                },
                timeGridDay: {
                    type: 'timeGrid',
                    duration: {days: 1},
                    buttonText: 'Jour',
                    dayMaxEventRows: 6
                },
                dayGridMonth: {
                    buttonText: 'Mois',
                },
            },
            events: myData,
            editable: true,
            eventResizableFromStart: true,
            dayMaxEventRows: true,
        });

        calendar.on('eventChange', (e) =>{
            let url = `/api/${e.event.id}/edit`
            let data = {
                start: e.event.start,
                end: e.event.end,
                description: e.event.title,
                backgroundColor: e.event.backgroundColor,
                borderColor: e.event.borderColor,
                textColor: e.event.textColor,
                allDay: e.event.allDay,
            }
            let xhr = new XMLHttpRequest();
            xhr.open('PUT', url, true);
            xhr.send(JSON.stringify(data));
        })

        calendar.render();
    });
}