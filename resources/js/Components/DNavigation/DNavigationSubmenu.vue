<script setup lang="ts">
import {Link} from "@inertiajs/vue3";
const props = defineProps<{
  node: object,
  index: number
}>()
function linkClicked(e, submenu, index, level) {
  if (submenu) {
    if(!level) {
      for (const [key, value] of Object.entries(refs.value)) {
        if(!key.includes('-' + index + '-') && key.includes('-arrow')) {
          refs.value[key].classList.remove('-rotate-90')
        }
        if(!key.includes('-' + index + '-') && key.includes('-visibility')) {
          refs.value[key].classList.add('hidden')
        }
      }
      refs.value['node-' + index + '-arrow'].classList.toggle('-rotate-90')
      refs.value['node-' + index + '-visibility'].classList.toggle('hidden')
    } else {
      refs.value['node-' + index + '-arrow'].classList.toggle('-rotate-90')
      refs.value['node-' + index + '-visibility'].classList.toggle('hidden')
    }

  }
}
function subIsActive(paths) {
  const activePaths = Array.isArray(paths) ? paths : [paths]

  return activePaths.some((path) => {
    return route().current().startsWith(path)
  })
}

</script>

<template>
  <div class="space-y-1">
    <button
        class="w-full text-left group flex items-center space-x-2 px-2.5 text-sm font-medium rounded-lg text-gray-800 border border-transparent hover:text-gray-900 hover:bg-blue-50 active:border-blue-100 dark:text-gray-200 dark:hover:text-white dark:hover:bg-gray-700/75 dark:active:border-gray-600"
        type="button" @click.prevent="linkClicked($event, true, index)">
          <span
              class="flex-none flex items-center text-gray-400 group-hover:text-digicomm-500 dark:text-gray-500 dark:group-hover:text-gray-300" v-if="node.icon"><font-awesome-icon
              :icon="node.icon"
              class="flex-none inline-block w-4 h-4"/></span>
      <span class="py-1 grow">{{ node.name }}</span>
      <span :ref="el => { refs['node-' + index + '-arrow'] = el }" class="flex-none opacity-75" :class="{
            '-rotate-90': subIsActive(node.subActivePaths)
          }">
            <font-awesome-icon
                :icon="['far','chevron-left']"
                class="flex-none inline-block w-4 h-4"/>
          </span>
    </button>

    <!--
      Submenu visibility
        Closed        'hidden'
        Opened        '' (no class)

      Show/Hide with transitions
        enter         'transition ease-out duration-100'
        enter-start   '-translate-y-5 opacity-0'
        enter-end     'translate-y-0 opacity-100'
        leave         'transition ease-in duration-100'
        leave-start   'translate-y-0 opacity-100'
        leave-end     '-translate-y-5 opacity-0'
    -->
    <div :ref="el => { refs['node-' + index + '-visibility'] = el }" class="relative z-0 ml-7" :class="{
            'hidden': !subIsActive(node.subActivePaths)
          }">

      <div v-for="(subnode, subindex) in node.sub" :key="`node-${index}-${subindex}`">
        <Link :class="{
            'border border-digicomm-100 bg-digicomm-50 dark:border-transparent dark:bg-gray-700/75': route().current() === subnode.to,
            'space-x-2': subnode.icon
          }"
              :href="route(subnode.to)"
              class="group flex items-center px-2.5 text-sm font-medium rounded-lg text-gray-800 border border-transparent hover:text-gray-900 hover:bg-digicomm-50 active:border-digicomm-100 dark:text-white dark:hover:text-white dark:hover:bg-gray-700/75 dark:active:border-gray-600"
        >
        <span
            class="flex-none flex items-center text-gray-400 group-hover:text-digicomm-500 dark:text-gray-500 dark:group-hover:text-gray-300"><font-awesome-icon
            :icon="subnode.icon"
            class="flex-none inline-block w-4 h-4" v-if="subnode.icon"/></span>
          <span class="py-1 grow">{{ subnode.name }}</span>

        </Link>

      </div>
    </div>

  </div>
</template>

<style scoped>

</style>