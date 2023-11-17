<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import {Link, router} from '@inertiajs/vue3';
import {useMainStore} from "@/js/Stores/main.js";

const store = useMainStore()
function logout() {
  store.$reset()
  router.post(route('logout'))
}
</script>

<template>
  <!-- User Dropdown -->
  <Menu as="div" class="relative inline-block">
    <!-- Dropdown Toggle Button -->
    <MenuButton
        class="inline-flex justify-center items-center space-x-2 border font-semibold rounded-lg px-3 py-2 leading-5 text-sm border-gray-200 bg-white text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700"
    >
      <svg class="hi-mini hi-user-circle inline-block w-5 h-5 sm:hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-5.5-2.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a5.99 5.99 0 00-4.793 2.39A6.483 6.483 0 0010 16.5a6.483 6.483 0 004.793-2.11A5.99 5.99 0 0010 12z" clip-rule="evenodd"/></svg>
      <span class="hidden sm:inline">{{ $page.props.auth.user.name }}</span>
      <svg class="hi-mini hi-chevron-down w-5 h-5 opacity-40 hidden sm:inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
    </MenuButton>
    <!-- END Dropdown Toggle Button -->

    <!-- Dropdown -->
    <Transition
        enter-active-class="transition ease-out duration-100"
        enter-from-class="opacity-0 scale-90"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition ease-in duration-75"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-90"
    >
      <MenuItems class="absolute right-0 origin-top-right z-10 mt-2 w-64 shadow-xl rounded-lg dark:shadow-gray-900 focus:outline-none">
        <div class="bg-white ring-1 ring-black ring-opacity-5 rounded-lg divide-y divide-gray-100 dark:bg-gray-800 dark:divide-gray-700 dark:ring-gray-700">
          <div class="px-5 py-3 flex items-center space-x-3">
            <img :src="$page.props.auth.user.profile_photo_url" alt="User Avatar" class="flex-none inline-block w-10 h-10 rounded-full">
            <div class="grow text-sm">
              <a href="javascript:void(0)" class="font-semibold text-gray-600 hover:text-gray-500 dark:text-gray-300 dark:hovertext-gray-400">{{ $page.props.auth.user.name }}</a>
              <p class="break-all text-xs font-medium text-gray-500 dark:text-gray-400">
                {{ $page.props.auth.user.email }}
              </p>
            </div>
          </div>
          <div class="p-2.5 space-y-1">
            <MenuItem v-slot="{ active }" as="template">
              <Link :href="route('profile.show')"
                  class="group text-sm font-medium flex items-center justify-between space-x-2 px-2.5 py-2 rounded-lg border border-transparent"
                  :class="{
                          [`text-digicomm-800 bg-digicomm-50 dark:text-white dark:bg-gray-700/75 dark:border-transparent`]: route().current() === 'profile.show',
                          [`text-gray-700 hover:text-digicomm-800 hover:bg-digicomm-50 hover:bg-digicomm-50 active:border-digicomm-100 dark:text-gray-200 dark:hover:text-white dark:hover:bg-gray-700/75 dark:active:border-gray-600`]: route().current() !== 'profile.show',
                        }"
              >
                <font-awesome-icon :icon="['far', 'circle-user']" class="flex-none hi-mini hi-inbox inline-block w-5 h-5 opacity-25 group-hover:opacity-50" />
                <span class="grow">Profile</span>
              </Link>
            </MenuItem>
            <MenuItem v-slot="{ active }" as="template" v-if="$page.props.jetstream.hasApiFeatures">
              <Link :href="route('api-tokens.index')"
                    class="group text-sm font-medium flex items-center justify-between space-x-2 px-2.5 py-2 rounded-lg border border-transparent"
                    :class="{
                          [`text-digicomm-800 bg-digicomm-50 dark:text-white dark:bg-gray-700/75 dark:border-transparent`]: route().current() === 'api-tokens.index',
                          [`text-gray-700 hover:text-digicomm-800 hover:bg-digicomm-50 hover:bg-digicomm-50 active:border-digicomm-100 dark:text-gray-200 dark:hover:text-white dark:hover:bg-gray-700/75 dark:active:border-gray-600`]: route().current() !== 'api-tokens.index',
                        }"
              >
                <font-awesome-icon :icon="['far', 'webhook']" class="flex-none hi-mini hi-inbox inline-block w-5 h-5 opacity-25 group-hover:opacity-50" />
                <span class="grow">API Tokens</span>
              </Link>
            </MenuItem>
          </div>
          <div class="p-2.5 space-y-1">
            <MenuItem v-slot="{ active }">
              <button class="block w-full group text-sm font-medium flex items-center justify-between space-x-2 px-2.5 py-2 rounded-lg border border-transparent text-gray-700 hover:text-digicomm-800 hover:bg-digicomm-50 hover:bg-digicomm-50 active:border-digicomm-100 dark:text-gray-200 dark:hover:text-white dark:hover:bg-gray-700/75 dark:active:border-gray-600" @click.prevent="logout">Log Out</button>
            </MenuItem>
          </div>
        </div>
      </MenuItems>
    </Transition>
    <!-- END Dropdown -->
  </Menu>
  <!-- END User Dropdown -->
</template>