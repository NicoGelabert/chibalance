<template>
  <div class="flex items-center justify-between mb-3">
    <h1 v-if="!loading" class="text-3xl font-semibold">
      {{ appointment.id ? `Update Appointment #${appointment.id}` : 'Create New Appointment' }}
    </h1>
  </div>

  <div class="bg-white rounded-lg shadow animate-fade-in-down">
    <Spinner
      v-if="loading"
      class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center z-50"
    />
    <form v-if="!loading" @submit.prevent="onSubmit" class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

      <!-- First name -->
      <CustomInput
        v-model="appointment.first_name"
        label="First Name"
        :errors="errors['first_name']"
      />

      <!-- Last name -->
      <CustomInput
        v-model="appointment.last_name"
        label="Last Name"
        :errors="errors['last_name']"
      />

      <!-- Email -->
      <CustomInput
        v-model="appointment.email"
        label="Email"
        type="email"
        :errors="errors['email']"
      />

      <!-- Contact number -->
      <CustomInput
        v-model="appointment.contact_number"
        label="Contact Number"
        type="text"
        :errors="errors['contact_number']"
      />

      <!-- Product -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Treatment / Product
        </label>

        <select
            v-if="products.length"
            v-model="appointment.product_id"
            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
        >
            <option disabled value="">Select a treatment</option>
            <option
            v-for="p in products"
            :key="p.id"
            :value="p.id"
            >
            {{ p.title }}
            </option>
        </select>

        <p v-if="errors['product_id']" class="text-red-500 text-xs mt-1">
            {{ errors['product_id'][0] }}
        </p>
        </div>



      <!-- Date -->
      <CustomInput
        v-model="appointment.date"
        label="Date"
        type="date"
        :errors="errors['date']"
      />

      <!-- Start time -->
      <CustomInput
        v-model="appointment.start_time"
        label="Start Time"
        type="time"
        :errors="errors['start_time']"
      />

      <!-- End time -->
      <CustomInput
        v-model="appointment.end_time"
        label="End Time"
        type="time"
        :errors="errors['end_time']"
      />

      <!-- Status -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
        <select
          v-model="appointment.status"
          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
        >
          <option value="pending">Pending</option>
          <option value="confirmed">Confirmed</option>
          <option value="cancelled">Cancelled</option>
        </select>
        <p v-if="errors['status']" class="text-red-500 text-xs mt-1">{{ errors['status'][0] }}</p>
      </div>

      <!-- Notes -->
      <div class="md:col-span-2">
        <CustomInput
          type="textarea"
          v-model="appointment.notes"
          label="Notes"
          :errors="errors['notes']"
        />
      </div>

      <!-- Footer -->
      <div class="md:col-span-2 flex justify-end gap-3 mt-6">
        <button
          type="submit"
          class="bg-black text-white px-4 py-2 rounded-md hover:bg-gray-800"
        >
          Save
        </button>

        <button
          type="button"
          @click="onSubmit($event, true)"
          class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-600"
        >
          Save & Close
        </button>

        <router-link
          :to="{ name: 'app.appointments' }"
          class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md border hover:bg-gray-50"
        >
          Cancel
        </router-link>
      </div>

    </form>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import CustomInput from '../../components/core/CustomInput.vue'
import Spinner from '../../components/core/Spinner.vue'
import store from '../../store'
import axiosClient from '../../axios'

const route = useRoute()
const router = useRouter()

const appointment = ref({
  id: null,
  first_name: '',
  last_name: '',
  email: '',
  contact_number: '',
  product_id: '',
  date: '',
  start_time: '',
  end_time: '',
  status: 'pending',
  notes: '',
  cancel_token: '',
  created_by: '',
  updated_by: '',
})

const errors = ref({})
const loading = ref(false)
const products = ref([])

onMounted(async () => {
  loading.value = true
  try {
    // Cargar productos primero
    const resProducts = await axiosClient.get('/products')
    products.value = resProducts.data.data

    // Si estamos editando, cargar la cita
    if (route.params.id) {
      const res = await store.dispatch('getAppointment', route.params.id)
      appointment.value = res.data.data
    }
  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
})


function onSubmit($event, close = false) {
  loading.value = true
  errors.value = {}

  const action = appointment.value.id ? 'updateAppointment' : 'createAppointment'
  store.dispatch(action, appointment.value)
    .then(res => {
      store.commit('showToast', `Appointment was successfully ${appointment.value.id ? 'updated' : 'created'}`)
      store.dispatch('getAppointments')
      if (close) router.push({ name: 'app.appointments' })
      else if (!appointment.value.id) {
        router.push({ name: 'app.appointments.edit', params: { id: res.data.id } })
      }
    })
    .catch(err => {
      errors.value = err.response?.data?.errors || {}
    })
    .finally(() => {
      loading.value = false
    })
}
</script>
