<script setup>
import {onBeforeUnmount, onMounted, ref} from "vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {usePage} from "@inertiajs/vue3";
import {Notification, NotificationGroup, notify} from 'notiwind';
let pusherChannel = ref('')
const page = usePage()
const svgList = {
  warning: 'exclamation',
  danger: 'do-not-enter',
  info: 'info',
  success: 'check',
}

const typeClass = {
  danger: 'bg-rose-700 dark:bg-rose-800',
  warning: 'bg-yellow-500 dark:bg-yellow-800',
  info: 'bg-blue-500 dark:bg-blue-800',
  success: 'bg-digicomm-700 dark:bg-digicomm-800',
}
const titleClass = {
  danger: 'text-rose-700 dark: text-rose-800',
  warning: 'text-yellow-500 dark:text-yellow-800',
  info: 'text-blue-500 dark:text-blue-800',
  success: 'text-digicomm-700 dark: text-digicomm-800',
}

onMounted(() => {
  pusherChannel.value = page.props.auth.user.username + '-' + page.props.session
  pusher.subscribe(pusherChannel.value)
  pusher.bind('App\\Events\\UserEvent', (data) => {
    notify({
      title: data.title,
      text: data.text,
      type: data.type,
      group: 'bottom',
    }, 10000)

  })
})
onBeforeUnmount(() => {
  pusher.unsubscribe(pusherChannel.value)
  pusher.unbind('App\\Events\\UserEvent')
})
</script>

<template>
  <NotificationGroup group="bottom" position="bottom">
    <div class="fixed inset-x-0 bottom-0 flex items-start justify-end p-6 px-4 py-6 pointer-events-none">
      <div class="w-full max-w-sm">
        <Notification
            v-slot="{ notifications, close }"
            enter="ease-out duration-300 transition"
            enter-from="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
            enter-to="translate-y-0 opacity-100 sm:translate-x-0"
            leave="transition ease-in duration-500"
            leave-from="opacity-100"
            leave-to="opacity-0"
            move="transition duration-500"
            move-delay="delay-300"
        >
          <div
              v-for="notification in notifications"
              :key="notification.id"
              class="flex w-full max-w-sm mt-4 overflow-hidden bg-white dark:bg-gray-300 rounded-lg shadow-lg pointer-events-auto ring-1 ring-black ring-opacity-5"
          >
            <div :class="typeClass[notification.type]" class="flex items-center justify-center shrink-0 w-12">

              <font-awesome-icon :icon="['far', svgList[notification.type]]"
                                 class="w-6 h-6 text-white fill-current"></font-awesome-icon>
            </div>
            <div class="grow pl-3 py-2 -mx-3">
              <div class="ml-3">
                <span :class="titleClass[notification.type]" class="font-semibold">{{ notification.title }}</span>
                <p class="text-sm text-justify text-gray-600">{{ notification.text }}</p>
              </div>
            </div>
            <div class="shrink-0 p-1.5">
              <button
                  class="inline-flex text-gray-400 bg-white dark:bg-gray-300 dark:text-gray-600 rounded-md hover:text-gray-600 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400"
                  @click="close(notification.id)">
                <span class="sr-only">Close</span>
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                     xmlns="http://www.w3.org/2000/svg">
                  <path clip-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        fill-rule="evenodd"/>
                </svg>
              </button>
            </div>
          </div>
        </Notification>
      </div>
    </div>
  </NotificationGroup>
</template>