<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calendario</title>

    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
</head>
<style>
  /* Aumentar la altura de las franjas horarias normales */
  .fc-timegrid-slot {
      height: 60px !important;
      min-height: 100px !important;
  }

  /* Ajustar el tamaño de las etiquetas de las horas */
  .fc-timegrid-axis {
      height: 30px !important;
      line-height: 80px !important;
  }

  /* Hacer que los eventos ocupen toda la franja */
  .fc-timegrid-slot-frame {
      height: 80px !important;
  }

  /* Ajustar el tamaño del texto de los eventos */
  .fc-event {
      font-size: 20px !important;
      padding: 10px !important;
      line-height: 20px !important;
      border-radius: 6px !important;
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2) !important;
      font-weight: bold !important;
  }

  /* Eliminar fondo amarillo del día actual */
  .fc-day-today {
      background: none !important;
  }

  /* Mejorar la visibilidad de los títulos de los días */
  .fc-col-header-cell {
      font-weight: bold !important;
      font-size: 15px;
  }

  .fc-event-title {
      font-size: 11px !important;
      font-weight: 500;
  }
</style>

<body>

    <div id="calendar"></div>

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.min.js'></script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialDate: '2024-01-01',
          initialView: 'timeGridWeek',
          headerToolbar: { left: '', center: '', right: '' },
        //   headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek' },
          buttonText: { today: 'Hoy', week: 'Semana', month: 'Mes' },
          locale: 'es',
          slotMinTime: '08:00:00',
          slotMaxTime: '23:00:00',
          slotDuration: '01:00:00',
          slotLabelInterval: '01:00:00',
          allDaySlot: false,
          editable: false,
          selectable: false,
          droppable: false,
          eventStartEditable: false,
          eventDurationEditable: false,
          height: 'auto',
          dayHeaderFormat: { weekday: 'long' },
          slotLabelFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
          events: '/eventos',
          hiddenDays: [0],
          eventClick: function(info) {
            alert('Evento: ' + info.event.title);
          },
          eventDidMount: function(info) {
            info.el.style.backgroundColor = '#3788d8';
          }
        });

        calendar.render();
      });
    </script>

</body>
</html>
