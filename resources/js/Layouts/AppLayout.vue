<script setup>
import {computed, onMounted, ref, watch} from 'vue';
import {Head, usePage} from '@inertiajs/vue3';
import BaseFooter from "@/js/Components/Base/BaseFooter.vue";
import BaseSidebar from "@/js/Components/Base/BaseSidebar.vue";
import BaseHeader from "@/js/Components/Base/BaseHeader.vue";
import BaseNotifications from "@/js/Components/Base/BaseNotifications.vue";
import {useMainStore} from "@/js/Stores/main.js";

const store = useMainStore()
const page = usePage()


const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  layout: {
    type: String,
    required: true,
    default: ''
  }
})

const desktopSidebarOpen = ref(true);
const mobileSidebarOpen = ref(false);

let layoutCss = computed(() => {
  switch (props.layout) {
    case 'boxed':
      return 'mx-auto p-1 w-full md:w-9/12'
    case 'right':
      return '';
    default:
      return 'mx-auto p-1 w-11/12'
  }
})
onMounted(() => {

})

if (store.getDarkModeSystem) {
  if ((window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) || page.props.auth.user.dark_mode) {
    store.setDarkMode(true);
  } else {
    store.setDarkMode(false);
  }
}

window
    .matchMedia('(prefers-color-scheme: dark)')
    .addEventListener('change', (e) => {
      console.log(window.matchMedia('(prefers-color-scheme: dark)'))
      if (store.getDarkModeSystem) {
        if (e.matches) {
          store.setDarkMode(true);
        } else {
          store.setDarkMode(false);
        }
      }
    });
</script>

<template>
  <Head :title="title"/>

  <!-- Page Container -->
  <div
      id="page-container"
      :class="{
      'lg:pl-64': desktopSidebarOpen
    }"
      class="flex flex-col mx-auto w-full min-h-screen min-w-[320px] bg-gray-100 dark:text-gray-100 dark:bg-gray-900"
  >
    <BaseSidebar :desktop-sidebar-open="desktopSidebarOpen" :mobile-sidebar-open="mobileSidebarOpen"
                 @toggle-desktop="desktopSidebarOpen = !desktopSidebarOpen"
                 @toggle-mobile="mobileSidebarOpen = !mobileSidebarOpen"/>

    <BaseHeader :desktop-sidebar-open="desktopSidebarOpen" :mobile-sidebar-open="mobileSidebarOpen"
                @toggle-desktop="desktopSidebarOpen = !desktopSidebarOpen"
                @toggle-mobile="mobileSidebarOpen = !mobileSidebarOpen"/>

    <main id="page-content" class="flex flex-auto flex-col lg:flex-row-reverse max-w-full pt-16">
      <div v-if="layout !== 'right'" :class="layoutCss">
        <slot/>
      </div>
      <slot v-else/>
    </main>


    <BaseFooter/>
  </div>
  <!-- END Page Container -->
  <BaseNotifications/>


</template>
