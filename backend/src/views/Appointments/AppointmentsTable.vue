<template>
  <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
    <!-- Header controls -->
    <div class="flex flex-col md:flex-row justify-between border-b-2 pb-3 gap-4">
      <div class="flex md:items-center flex-col md:flex-row gap-4">
        <span class="whitespace-nowrap mr-3">Per Page</span>
        <select @change="getAppointments(null)" v-model="perPage"
          class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
        <span class="ml-3">Found {{ appointments.total }} appointments</span>
      </div>

      <div>
        <input
          v-model="search"
          @change="getAppointments(null)"
          class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
          placeholder="Search appointments"
        >
      </div>
    </div>

    <!-- Table -->
    <table class="table-auto w-full mt-3">
      <thead class="hidden md:contents">
        <tr>
          <TableHeaderCell field="id" :sort-field="sortField" :sort-direction="sortDirection" @click="sortAppointments('id')">
            Id
          </TableHeaderCell>
          <TableHeaderCell field="date" :sort-field="sortField" :sort-direction="sortDirection" @click="sortAppointments('date')">
            Date
          </TableHeaderCell>
          <TableHeaderCell field="first_name" :sort-field="sortField" :sort-direction="sortDirection" @click="sortAppointments('first_name')">
            First Name
          </TableHeaderCell>
          <TableHeaderCell field="last_name" :sort-field="sortField" :sort-direction="sortDirection" @click="sortAppointments('last_name')">
            Last Name
          </TableHeaderCell>
          <TableHeaderCell field="product_title" :sort-field="sortField" :sort-direction="sortDirection" @click="sortAppointments('product_title')">
            Treatment
          </TableHeaderCell>
          <TableHeaderCell field="status" :sort-field="sortField" :sort-direction="sortDirection" @click="sortAppointments('status')">
            Status
          </TableHeaderCell>
          <TableHeaderCell field="actions">
            Actions
          </TableHeaderCell>
        </tr>
      </thead>

      <tbody v-if="appointments.loading || !appointments.data.length">
        <tr>
          <td colspan="5">
            <Spinner v-if="appointments.loading" />
            <p v-else class="text-center py-8 text-gray-700">There are no appointments</p>
          </td>
        </tr>
      </tbody>

      <tbody v-else>
        <tr v-for="(appointment, index) in appointments.data" :key="index">
          <td class="border-b p-2">{{ appointment.id }}</td>
          <td class="border-b p-2">{{ appointment.date }}</td>
          <td class="border-b p-2">{{ appointment.first_name }}</td>
          <td class="border-b p-2">{{ appointment.last_name }}</td>
          <td class="border-b p-2">{{ appointment.product_title }}</td>
          <td class="border-b p-2 capitalize">
            <span
                :class="[
                    'px-2 py-1 rounded text-xs font-semibold',
                    {
                        'bg-green-100 text-green-700': appointment.status === 'confirmed',
                        'bg-red-100 text-red-700': appointment.status === 'cancelled',
                        'bg-yellow-100 text-yellow-700': appointment.status === 'pending',
                    }
                ]"
            >
              {{ appointment.status }}
            </span>
          </td>
          <td class="border-b p-2">
            <ActionMenu
              :editType="'router-link'"
              :editTo="{ name: 'app.appointments.edit', params: { id: appointment.id } }"
              :remove="() => deleteAppointment(appointment)"
            />
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <div v-if="!appointments.loading" class="flex flex-col md:flex-row gap-4 justify-between items-center mt-5">
      <div v-if="appointments.data.length">
        Showing from {{ appointments.from }} to {{ appointments.to }}
      </div>

      <nav
        v-if="appointments.total > appointments.limit"
        class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
        aria-label="Pagination"
      >
        <a
          v-for="(link, i) of appointments.links"
          :key="i"
          :disabled="!link.url"
          href="#"
          @click="getForPage($event, link)"
          class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
          :class="[
            link.active
              ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
              : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
            i === 0 ? 'rounded-l-md' : '',
            i === appointments.links.length - 1 ? 'rounded-r-md' : '',
            !link.url ? ' bg-gray-100 text-gray-700': '',
            (link.label.includes('Previous') || link.label.includes('Next')) ? 'hidden md:inline' : ''
          ]"
          v-html="link.label"
        ></a>
      </nav>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import store from "../../store";
import Spinner from "../../components/core/Spinner.vue";
import TableHeaderCell from "../../components/core/Table/TableHeaderCell.vue";
import ActionMenu from "../../components/core/ActionMenu.vue";

const perPage = ref(10);
const search = ref('');
const appointments = computed(() => store.state.appointments);
const sortField = ref('id');
const sortDirection = ref('desc');

onMounted(() => {
  getAppointments();
});

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) return;
  getAppointments(link.url);
}

function getAppointments(url = null) {
  store.dispatch("getAppointments", {
    url,
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value
  });
}

function sortAppointments(field) {
  if (field === sortField.value) {
    sortDirection.value = sortDirection.value === 'desc' ? 'asc' : 'desc';
  } else {
    sortField.value = field;
    sortDirection.value = 'asc';
  }
  getAppointments();
}

function deleteAppointment(appointment) {
  if (!confirm(`Are you sure you want to delete this appointment?`)) return;
  store.dispatch('deleteAppointment', appointment.id)
    .then(() => {
      store.commit('showToast', 'Appointment was successfully deleted');
      store.dispatch('getAppointments');
    });
}
</script>
