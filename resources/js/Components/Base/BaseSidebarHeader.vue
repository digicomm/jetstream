<script setup>
import DImg from "@/js/Components/DImg.vue";

defineEmits(['toggleDesktop','toggleMobile'])
import { Link as ILink } from '@inertiajs/vue3';
</script>

<template>
  <!-- Sidebar Header -->
  <div
      class="h-16 flex flex-none items-center justify-between lg:justify-center px-4 w-full dark:bg-gray-600 dark:bg-opacity-25">
    <ILink class="group inline-flex items-center space-x-0 font-bold text-lg tracking-wide text-gray-900 hover:text-gray-600 dark:text-gray-100 dark:hover:text-gray-300"
       :href="route('dashboard')">
      <DImg src="/digicomm_map.png" img-class="h-10 inline dark:invert"/>
      <DImg src="/digicomm_banner.png" img-class="h-10 inline"/>
    </ILink>
    <div class="lg:hidden">
      <button class="inline-flex justify-center items-center space-x-2 border font-semibold rounded-lg px-3 py-2 leading-5 text-sm border-gray-200 bg-white text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700"
              type="button" @click="$emit('toggleMobile')">
        <svg aria-hidden="true" class="hi-mini hi-x-mark inline-block w-5 h-5 -mx-0.5"
             fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <path
              d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"></path>
        </svg>
      </button>
    </div>
  </div>
  <!-- END Sidebar Header -->
</template>

<style scoped>

</style>