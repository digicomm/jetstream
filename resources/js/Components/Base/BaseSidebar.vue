<script setup>
import BaseSidebarHeader from "@/js/Components/Base/BaseSidebarHeader.vue";
import {Link} from "@inertiajs/vue3";
import {ref} from "vue";
import BaseNavigationVertical from "@/js/Components/Base/BaseNavigationVertical.vue";
import menu from "@/js/Data/menu"
const nodes = menu.main;

defineProps({
  mobileSidebarOpen: {
    type: Boolean,
    required: true
  },
  desktopSidebarOpen: {
    type: Boolean,
    required: true
  },
})
defineEmits(['toggleDesktop', 'toggleMobile'])
</script>

<template>
  <!-- Page Sidebar -->
  <nav
      id="page-sidebar"
      :class="{
        '-translate-x-full': !mobileSidebarOpen,
        'translate-x-0': mobileSidebarOpen,
        'lg:-translate-x-full': !desktopSidebarOpen,
        'lg:translate-x-0': desktopSidebarOpen,
      }"
      aria-label="Main Sidebar Navigation"
      class="flex flex-col fixed top-0 left-0 bottom-0 w-full lg:w-64 h-full bg-white border-r border-gray-200 z-50 transform transition-transform duration-500 ease-out dark:bg-gray-800 dark:text-gray-200 dark:border-gray-800"
  >
    <BaseSidebarHeader @toggle-mobile="$emit('toggleMobile')" @toggle-desktop="$emit('toggleDesktop')"/>

    <!-- Main Navigation -->

    <div class="w-full grow space-y-3 p-2">
      <div class="overflow-y-auto">
        <BaseNavigationVertical :nodes="nodes"></BaseNavigationVertical>
      </div>
    </div>
    <!-- END Main Navigation -->

    <!-- Sub Navigation -->
    <div class="w-full flex-none space-y-3 p-4">

    </div>
    <!-- END Sub Navigation -->
  </nav>
  <!-- Page Sidebar -->
</template>

<style scoped>

</style>