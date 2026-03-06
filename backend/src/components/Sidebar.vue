<template>

    <div class="min-w-[140px] md:min-w-[160px] w-[25%] pt-8 pl-2 md:pl-8 transition-all" id="sideBarContainer" >
        <button id="toggleIconButton" @click="emit('toggle-sidebar', toggleChevronLeftIcon()) " class="flex items-center justify-center rounded transition-colors w-8 h-8 mr-2 text-white bg-black hover:text-black hover:bg-white">
            <div :class="[toggleIconLeft ? 'iconOpen' : 'iconClosed']" >
                <ChevronLeftIcon class="rotate-180 md:rotate-0 w-6"/>
            </div>
        </button>
        <router-link v-for="item in menuItems" :key="item.name" :to="{ name: item.route }"
            class="flex items-center justify-between p-2 w-full rounded transition-colors hover:bg-white">
            <span class="text-sm">
                {{ item.label }}
            </span>
            <span class="mr-2">
                <component :is="item.icon" class="w-5" />
            </span>
        </router-link>
    </div>
  
</template>

<script setup>

import { 
    ArchiveBoxIcon,
    BuildingStorefrontIcon,
    CalendarDaysIcon,
    ChevronLeftIcon,
    FingerPrintIcon,
    IdentificationIcon,
    MegaphoneIcon,
    NewspaperIcon,
    PhotoIcon,
    TrophyIcon,
    UsersIcon
} from '@heroicons/vue/24/outline'
import {ref} from "vue";

const emit = defineEmits(['toggle-sidebar'])

const toggleIconLeft = ref(true);
const toggleChevronLeftIcon = () => {
  toggleIconLeft.value = !toggleIconLeft.value;
}

// Lista de ítems del menú (más organizado)
const menuItems = ref([
    { name: 'dashboard', label: 'Dashboard', route: 'app.dashboard', icon: BuildingStorefrontIcon },
    { name: 'homeherobanners', label: 'Home Hero Banner', route: 'app.homeherobanners', icon: PhotoIcon },
    { name: 'categories', label: 'Categories', route: 'app.categories', icon: MegaphoneIcon },
    { name: 'products', label: 'Treatments', route: 'app.products', icon: ArchiveBoxIcon },
    { name: 'appointments', label: 'Appointments', route: 'app.appointments', icon: CalendarDaysIcon },
    { name: 'abouts', label: 'About', route: 'app.abouts', icon: FingerPrintIcon },
    { name: 'articles', label: 'Articles', route: 'app.articles', icon: NewspaperIcon },
    { name: 'authors', label: 'Author', route: 'app.authors', icon: IdentificationIcon },
    { name: 'clients', label: 'Clients', route: 'app.clients', icon: TrophyIcon },
    { name: 'users', label: 'Users', route: 'app.users', icon: UsersIcon },
]);

</script>

<style scoped lang="scss">

#sideBarContainer{
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap:1rem;
  a{
    width: 100%;    
  }
}
.iconOpen{
  transform: rotate(0);
  transition: transform 0.25s cubic-bezier(0.79, 0.33, 0.14, 0.53);
}
.iconClosed{
  transform: rotate(180deg);
  transition: transform 0.25s cubic-bezier(0.79, 0.33, 0.14, 0.53);
}
</style>