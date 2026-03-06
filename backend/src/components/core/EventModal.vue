<template>
  <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
      <slot></slot>
      <div class="mt-4 flex justify-end space-x-2">
        <button @click="$emit('close')" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancelar</button>
        <button v-if="actionText" @click="$emit('action')" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">{{ actionText }}</button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({ show: Boolean, actionText: String })
</script>
