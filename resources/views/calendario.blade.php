<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Reserva de Losa | Calendario</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- FullCalendar CSS -->
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<style>
  .fc-timegrid-slot {
    height: 50px !important;
  }

  .fc-timegrid-slot-label {
    font-size: 1rem;
    padding-top: 0.5rem;
  }
</style>


<body class="bg-gray-100 font-sans">

  <div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Encabezado con logo y título -->
    <div class="flex items-center justify-between mb-2">
      <div class="flex items-center space-x-4 mb-6">
        <img src="{{ asset('/uma/img/logo-uma.png') }}" alt="Logo UMA" class="w-16 h-16 object-contain drop-shadow-md">
        <h1 class="text-2xl md:text-3xl font-bold text-[#E40D5E] leading-snug">
          Reserva tu Hora en la Losa UMA<br class="hidden md:block" />
          <span class="text-gray-800 text-lg md:text-xl font-medium block">¡El Deporte te Espera!</span>
        </h1>
      </div>
    </div>

    <!-- Contenedor del calendario -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-[#E40D5E]">
      <div id="calendar" class="rounded-lg overflow-hidden border border-gray-300"></div>
    </div>
  </div>  

  <!-- FullCalendar JS -->
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.min.js'></script>

  <script>

  document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'timeGridWeek',
      initialDate: new Date(), // o '2025-05-19'
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'timeGridWeek'
      },
      buttonText: { today: 'Hoy', week: 'Semana', month: 'Mes' },
      locale: 'es',
      slotMinTime: '08:00:00',
      slotMaxTime: '23:00:00',
      allDaySlot: false, // 🔥 Quita la fila de eventos de día completo
      hiddenDays: [0],   // 🔥 Oculta los domingos (0 = domingo, 6 = sábado)
      dayHeaderFormat: { weekday: 'long' }, // 🔥 Solo muestra "lunes", "martes", etc.
      slotLabelFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
      slotDuration: '01:00:00',
      events: '/eventos',
      selectable: true,
      select: function(info) {
        abrirModalReserva(info.startStr, info.endStr);
      },

      eventDidMount: function(info) {
        const color = info.event.backgroundColor || info.event.extendedProps.color || '#E40D5E';

        info.el.style.backgroundColor = color;
        info.el.style.color = '#fff';
        info.el.style.border = 'none';
        info.el.style.padding = '8px';
        info.el.style.borderRadius = '0.5rem';
        info.el.style.boxShadow = '0px 2px 6px rgba(0, 0, 0, 0.2)';
      }
    });

    calendar.render();
  });

  function enviarReserva(e) {
    e.preventDefault();

    const form = document.getElementById('formReserva');
    const formData = new FormData(form);

    fetch(form.action, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    })
    .then(response => {
      if (!response.ok) throw response;
      return response.json ? response.json() : response;
    })
    .then(() => {
      Swal.fire({
        icon: 'success',
        title: '¡Reserva registrada!',
        showConfirmButton: false,
        timer: 1500
      });
      cerrarModalReserva();
      form.reset();
      setTimeout(() => window.location.reload(), 1600); // recarga para ver evento
    })
    .catch(async (error) => {
      let mensaje = 'Ocurrió un error al guardar la reserva.';
      if (error.json) {
        const errData = await error.json();
        if (errData.errors) {
          mensaje = Object.values(errData.errors).flat().join('<br>');
        }
      }

      Swal.fire({
        icon: 'error',
        title: 'Error de validación',
        html: mensaje
      });
    });
  }

  function abrirModalReserva(start, end) {
  // Formatear a 'yyyy-MM-ddTHH:mm'
  document.getElementById('fecha_inicio').value = start.slice(0, 16);
  document.getElementById('fecha_fin').value = end.slice(0, 16);

  document.getElementById('modalReserva').classList.remove('hidden');
  }

  function cerrarModalReserva() {
    document.getElementById('modalReserva').classList.add('hidden');
  }

  function abrirModalReserva(start, end) {
    document.getElementById('formReserva').reset();
    document.getElementById('fecha_inicio').value = start.slice(0, 16);
    document.getElementById('fecha_fin').value = end.slice(0, 16);
    document.getElementById('modalReserva').classList.remove('hidden');
  }

  </script>

  <!-- MODAL DE RESERVA -->
<div id="modalReserva" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 relative">
    <button onclick="cerrarModalReserva()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-xl">&times;</button>
    <h2 class="text-2xl font-bold text-[#E40D5E] mb-4">Registrar Reserva</h2>
    <form id="formReserva" method="POST" action="{{ route('reservar') }}" class="space-y-4" onsubmit="enviarReserva(event)">
      @csrf
      <input type="text" name="nombre" placeholder="Nombre" class="w-full border p-2 rounded" required>
      <input type="number" name="dni" placeholder="DNI" class="w-full border p-2 rounded" required>
      {{-- <input type="text" name="especialidad" placeholder="Especialidad" class="w-full border p-2 rounded" required> --}}
      <input type="number" name="telefono" placeholder="Teléfono" class="w-full border p-2 rounded" required>
      <textarea name="descripcion" placeholder="Descripción" class="w-full border p-2 rounded"></textarea>
      <input type="datetime-local" name="fecha_inicio" id="fecha_inicio" class="w-full border p-2 rounded" required>
      <input type="datetime-local" name="fecha_fin" id="fecha_fin" class="w-full border p-2 rounded" required>
      <button type="submit" class="bg-[#E40D5E] text-white px-4 py-2 rounded w-full">Guardar Reserva</button>
    </form>
  </div>
</div>

</body>
</html>

