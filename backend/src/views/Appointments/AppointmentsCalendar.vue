<template>
  <div class="bg-white p-4 rounded-lg shadow">
    <!-- Botón para crear un nuevo turno puntual -->
    <div class="mb-4 flex justify-end">
      <button
        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
        @click="openSlotForm"
      >
        Crear nuevo turno
      </button>
    </div>

    <!-- Calendario -->
    <FullCalendar ref="calendarRef" :options="calendarOptions" />

    <!-- Modal para editar o cancelar turno -->
    <EventModal
      :show="modalVisible"
      actionText="Guardar cambios"
      @close="modalVisible = false"
      @action="updateEventFromModal"
    >
      <h3 class="text-lg font-semibold mb-4">{{ modalData.title }}</h3>

      <div class="space-y-2">
        <div>
          <label class="block text-sm font-medium">Fecha</label>
          <input v-model="modalData.date" type="date" class="w-full border rounded px-2 py-1"/>
        </div>
        <div>
          <label class="block text-sm font-medium">Hora inicio</label>
          <input v-model="modalData.start_time" type="time" class="w-full border rounded px-2 py-1"/>
        </div>
        <div>
          <label class="block text-sm font-medium">Hora fin</label>
          <input v-model="modalData.end_time" type="time" class="w-full border rounded px-2 py-1"/>
        </div>
        <div v-if="modalData.type === 'appointment'">
          <label class="block text-sm font-medium">Status</label>
          <select v-model="modalData.status" class="w-full border rounded px-2 py-1">
            <option value="confirmed">Confirmada</option>
            <option value="pending">Pendiente</option>
            <option value="cancelled">Cancelada</option>
          </select>
        </div>
      </div>

      <button
        @click="cancelEvent"
        class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
      >
        Cancelar turno
      </button>
    </EventModal>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import axiosClient from "../../axios.js"
import EventModal from '../../components/core/EventModal.vue'

// Ref al calendario
const calendarRef = ref(null)

// Modal
const modalVisible = ref(false)
const modalData = ref({
  type: '', // 'appointment' o 'slot'
  id: null,
  title: '',
  date: '',
  start_time: '',
  end_time: '',
  status: ''
})

// Opciones del calendario
const calendarOptions = ref({
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
  initialView: 'timeGridWeek',
  editable: true,
  selectable: false,
  height: 'auto',

  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay'
  },

  events: fetchEvents,
  eventClick: handleEventClick,
  eventDrop: handleEventUpdate,
  eventResize: handleEventUpdate
})

// --- Traer eventos (citas + slots)
async function fetchEvents(fetchInfo, successCallback) {
  const { data } = await axiosClient.get('/appointments', {
    params: {
      start: fetchInfo.startStr,
      end: fetchInfo.endStr
    }
  })

  const events = []

  // Citas existentes → azules
  data.appointments?.forEach(a => {
    events.push({
      id: 'a-' + a.id,
      title: `${a.first_name} ${a.last_name}`,
      start: a.date + 'T' + a.start_time,
      end: a.date + 'T' + a.end_time,
      backgroundColor: '#3b82f6',
      borderColor: '#2563eb',
      extendedProps: { type: 'appointment', status: a.status }
    })
  })

  // Slots (automáticos ya guardados + slots puntuales) → verdes
  data.slots?.forEach(s => {
    // ⚡ solo agregar si tiene ID
    if (!s.id) return;

    events.push({
        id: 's-' + s.id,
        title: 'Disponible',
        start: s.date + 'T' + s.start_time,
        end: s.date + 'T' + s.end_time,
        backgroundColor: '#bbf7d0',
        borderColor: '#16a34a',
        extendedProps: { type: 'slot' }
    })
    })


  successCallback(events)
}

// --- Click sobre un evento → abrir modal
function handleEventClick(info) {
  const e = info.event

  modalData.value = {
    type: e.extendedProps.type,
    id: e.id,
    title: e.title,
    date: e.start.toISOString().slice(0,10),
    start_time: e.start.toISOString().slice(11,16),
    end_time: e.end.toISOString().slice(11,16),
    status: e.extendedProps.status || ''
  }
  modalVisible.value = true
}

// --- Guardar cambios desde modal
async function updateEventFromModal() {
  if(modalData.value.type === 'appointment') {
    await axiosClient.put(`/appointments/${modalData.value.id.replace('a-','')}`, {
      date: modalData.value.date,
      start_time: modalData.value.start_time,
      end_time: modalData.value.end_time,
      status: modalData.value.status
    })
  } else if(modalData.value.type === 'slot') {
    await axiosClient.put(`/timeslots/${modalData.value.id.replace('s-','')}`, {
      date: modalData.value.date,
      start_time: modalData.value.start_time,
      end_time: modalData.value.end_time
    })
  }
  modalVisible.value = false
  calendarRef.value.getApi().refetchEvents()
}

// --- Cancelar turno desde modal
async function cancelEvent() {
  if(modalData.value.type === 'appointment') {
    await axiosClient.delete(`/appointments/${modalData.value.id.replace('a-','')}`)
  } else if(modalData.value.type === 'slot') {
    await axiosClient.delete(`/timeslots/${modalData.value.id.replace('s-','')}`)
  }
  modalVisible.value = false
  calendarRef.value.getApi().refetchEvents()
}

// --- Drag & resize de citas
async function handleEventUpdate(info) {
  const e = info.event
  if(e.extendedProps.type !== 'appointment') return

  await axiosClient.put(`/appointments/${e.id.replace('a-','')}`, {
    start_time: e.start.toISOString().slice(11,16),
    end_time: e.end.toISOString().slice(11,16),
    date: e.start.toISOString().slice(0,10)
  })

  info.view.calendar.refetchEvents()
}

// --- Crear nuevo turno puntual
function openSlotForm() {
  const date = prompt('Fecha del turno (YYYY-MM-DD)')
  const start = prompt('Hora inicio (HH:MM)')
  const end = prompt('Hora fin (HH:MM)')

  if(!date || !start || !end) return

  axiosClient.post('/timeslots', {
    date,
    start_time: start,
    end_time: end
  }).then(() => {
    calendarRef.value.getApi().refetchEvents()
    alert('Turno puntual creado correctamente')
  })
}
</script>
