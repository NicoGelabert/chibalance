<template>
  <transition name="fade">
    <div
      v-if="show"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
      <div
        class="bg-white rounded-lg shadow-lg p-4 m-4 md:m-auto w-full max-w-lg relative max-h-[90vh] overflow-y-auto"
      >
        <!-- Close button -->
        <button
          @click="closeModal"
          class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl"
        >
          &times;
        </button>

        <!-- Title -->
        <h2 class="text-lg font-bold mb-3">Book: {{ product.title }}</h2>

        <div class="flex flex-col gap-4">
          <!-- Date picker -->
          <div class="relative w-full">
            <flat-pickr
              v-model="selectedDate"
              :config="dateConfig"
              placeholder="Choose date"
              class="border rounded p-1 text-sm w-full pr-8 cursor-pointer"
              @on-change="fetchSlots"
            />
            <span
              class="absolute right-1 top-1/2 transform -translate-y-1/2 text-gray-600 pointer-events-none"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
              </svg>
            </span>
          </div>

          <!-- Time slots -->
          <div class="mb-3">
            <div v-if="loadingSlots" class="text-xs text-gray-500">
              Loading slots...
            </div>

            <div
              v-else-if="allSlots.length === 0"
              class="text-xs text-gray-500"
            >
              No available slots
            </div>

            <div v-else class="flex flex-wrap gap-1">
              <button
                v-for="slot in allSlots"
                :key="slot"
                type="button"
                @click="!isBooked(slot) && (selectedSlot = slot)"
                :disabled="isBooked(slot)"
                :class="[
                  'py-1 px-2 border rounded text-xs transition-colors',
                  isBooked(slot)
                    ? 'bg-gray-200 text-gray-400 opacity-60 cursor-not-allowed'
                    : selectedSlot === slot
                      ? 'bg-blue-500 text-white'
                      : 'hover:bg-blue-100'
                ]"
              >
                {{ slot }}
              </button>
            </div>
          </div>

          <!-- Form fields -->
          <div class="grid md:grid-cols-2 gap-2 text-sm">
            <div>
              <input
                type="text"
                v-model="form.first_name"
                class="border rounded w-full text-sm"
                placeholder="First name"
              />
            </div>
            <div>
              <input
                type="text"
                v-model="form.last_name"
                class="border rounded w-full text-sm"
                placeholder="Last name"
              />
            </div>
          </div>

          <input
            type="email"
            v-model="form.email"
            class="border rounded w-full text-sm mt-2"
            placeholder="Email"
          />
          <input
            type="text"
            v-model="form.contact_number"
            class="border rounded w-full text-sm mt-2"
            placeholder="Contact number"
          />
          <textarea
            v-model="form.notes"
            class="border rounded w-full text-sm mt-2"
            placeholder="Notes (optional)"
          ></textarea>

          <button
            @click="submit"
            :disabled="submitting"
            class="btn btn-primary text-sm px-4 py-2"
          >
            {{ submitting ? 'Submitting...' : 'Confirm Booking' }}
          </button>

          <div v-if="message" class="mt-1 text-xs text-red-500">
            {{ message }}
          </div>
          <div v-if="successMessage" class="mt-1 text-xs text-green-600">
            {{ successMessage }}
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import axios from "axios";
import dayjs from "dayjs";
import FlatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";

export default {
  name: "BookingModal",
  components: { FlatPickr },
  props: { product: { type: Object, required: true } },
  data() {
    return {
      show: true,
      selectedDate: null,
      allSlots: [],
      booked: [],
      selectedSlot: null,
      loadingSlots: false,
      form: {
        first_name: "",
        last_name: "",
        email: "",
        contact_number: "",
        notes: "",
      },
      submitting: false,
      message: "",
      successMessage: "",
      unavailableDates: [],
      specificBlockedDates: [
        `${dayjs().year()}-12-25`,
        `${dayjs().year()}-12-31`,
        `${dayjs().year()}-01-01`,
        `${dayjs().year() + 1}-01-01`,
      ],
      dateConfig: {
        minDate: "today",
        maxDate: dayjs().add(60, "day").format("YYYY-MM-DD"),
        disable: [(date) => this.isDateBlocked(date)],
        locale: { firstDayOfWeek: 1 },
      },
    };
  },
  methods: {
    closeModal() {
      this.show = false;
      this.$emit("close");
    },

    isDateBlocked(date) {
      const formatted = dayjs(date).format("YYYY-MM-DD");
      if (date.getDay() === 0) return true;
      if (this.unavailableDates.includes(formatted)) return true;
      if (this.specificBlockedDates.includes(formatted)) return true;
      return false;
    },

    async fetchSlots() {
      if (!this.selectedDate) return;

      this.loadingSlots = true;
      this.allSlots = [];
      this.booked = [];
      this.selectedSlot = null;

      try {
        const selected = Array.isArray(this.selectedDate)
          ? this.selectedDate[0]
          : this.selectedDate;
        const formattedDate = dayjs(selected).format("YYYY-MM-DD");

        const res = await axios.get("/availability", {
          params: { date: formattedDate, product_id: this.product.id },
        });

        this.booked = (res.data.booked || []).map((t) => t.substr(0, 5));
        this.allSlots = (res.data.all || []).map((t) => t.substr(0, 5));

        if (this.allSlots.length > 0) {
          if (this.booked.length >= this.allSlots.length) {
            if (!this.unavailableDates.includes(formattedDate)) {
              this.unavailableDates.push(formattedDate);
            }
          } else {
            this.unavailableDates = this.unavailableDates.filter(
              (d) => d !== formattedDate
            );
          }
        }

        this.dateConfig = { ...this.dateConfig };
      } catch (err) {
        console.error(err);
      } finally {
        this.loadingSlots = false;
      }
    },

    isBooked(slot) {
      return this.booked.includes(slot);
    },

    async submit() {
      this.message = "";
      this.successMessage = "";

      if (!this.selectedDate) {
        this.message = "Please select a date";
        return;
      }
      if (!this.selectedSlot) {
        this.message = "Please select a time slot";
        return;
      }
      if (!this.form.first_name) {
        this.message = "Please enter your first name";
        return;
      }
      if (!this.form.last_name) {
        this.message = "Please enter your last name";
        return;
      }
      if (!this.form.email) {
        this.message = "Please enter your email";
        return;
      }
      if (!this.form.contact_number) {
        this.message = "Please enter your contact number";
        return;
      }

      this.submitting = true;
      try {
        const formattedDate = dayjs(this.selectedDate).format("YYYY-MM-DD");
        await axios.post("/appointments", {
          first_name: this.form.first_name,
          last_name: this.form.last_name,
          email: this.form.email,
          contact_number: this.form.contact_number,
          notes: this.form.notes,
          date: formattedDate,
          start_time: this.selectedSlot + ":00",
          product_id: this.product.id,
        });

        this.successMessage =
          "Appointment successfully created. You will receive a confirmation by email.";
        this.form = {
          first_name: "",
          last_name: "",
          email: "",
          contact_number: "",
          notes: "",
        };
        this.selectedSlot = null;
        this.allSlots = [];
        this.selectedDate = null;
      } catch (err) {
        console.error(err);
        if (err.response?.data?.errors) {
          this.message = Object.values(err.response.data.errors)
            .flat()
            .join(" ");
        } else {
          this.message =
            err.response?.data?.message || "Error creating the appointment";
        }
      } finally {
        this.submitting = false;
      }
    },
  },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
