<template>
    <div>
        <div class="flex justify-between mb-3">
            <h1 class="text-3xl font-semibold">Abouts</h1>
            <router-link :to="{name: 'app.abouts.create'}"
                          class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-black hover:text-black hover:bg-white focus:outline-none"
            >
            Add new About
            </router-link>
        </div>
        <AboutsTable/>
    </div>
  
  </template>
  
  <script setup>
  import {computed} from "vue";
  import store from "../../store";
  import AboutsTable from "./AboutsTable.vue";
  
  const abouts = computed(() => store.state.abouts);
  </script>