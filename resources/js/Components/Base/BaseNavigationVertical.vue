<script lang="ts" setup>
import {Link} from "@inertiajs/vue3";
import {onBeforeUpdate, reactive, ref} from "vue";

const list = reactive([1])
const refs = ref({})


const props = defineProps({
  nodes: {
    type: Array,
    description: "The nodes of the navigation"
  },
  subMenu: {
    type: Boolean,
    default: false,
    description: "If true, a submenu will be rendered",
  },
})

function linkClicked(e, submenu, index, subindex) {
  if (submenu) {
    if (!subindex) {

      for (const [key, value] of Object.entries(refs.value)) {
        if (!key.includes('-' + index + '-') && key.includes('-arrow')) {
          refs.value[key].classList.remove('-rotate-90')
        }
        if (!key.includes('-' + index + '-') && key.includes('-visibility')) {
          refs.value[key].classList.add('hidden')
        }
      }
      refs.value['node-' + index + '-arrow'].classList.toggle('-rotate-90')
      refs.value['node-' + index + '-visibility'].classList.toggle('hidden')
    } else {

      refs.value['node-' + index + '-' + subindex + '-arrow'].classList.toggle('-rotate-90')
      refs.value['node-' + index + '-' + subindex + '-visibility'].classList.toggle('hidden')
    }

  }
}

function subIsActive(paths) {
  const activePaths = Array.isArray(paths) ? paths : [paths]

  return activePaths.some((path) => {
    return route().current().startsWith(path)
  })
}

onBeforeUpdate(() => {
  refs.value = {}
})


</script>

<template>
  <nav class="space-y-1">
    <div v-for="(node, index) in nodes"
         :key="`node-${index}`">

      <!-- Regular Link -->
      <Link v-if="node.to && !node.subActivePaths"
            :class="{
            'border border-digicomm-100 bg-digicomm-50 dark:border-transparent dark:bg-gray-700/75': route().current(node.to),
            'space-x-2': node.icon
          }"
            :href="route(node.to)"
            class="group flex items-center px-2.5 text-sm font-medium rounded-lg text-gray-800 border border-transparent hover:text-gray-900 hover:bg-digicomm-50 active:border-digicomm-100 dark:text-white dark:hover:text-white dark:hover:bg-gray-700/75 dark:active:border-gray-600"
      >
        <span
            v-if="node.icon"
            class="flex-none flex items-center text-gray-400 group-hover:text-digicomm-500 dark:text-gray-500 dark:group-hover:text-gray-300"><font-awesome-icon
            :icon="node.icon"
            class="flex-none inline-block w-4 h-4"/></span>
        <span class="py-1 grow">{{ node.name }}</span>

      </Link>

      <div v-else-if="!node.to && node.subActivePaths" class="space-y-1">
        <button
            class="w-full text-left group flex items-center space-x-2 px-2.5 text-sm font-medium rounded-lg text-gray-800 border border-transparent hover:text-gray-900 hover:bg-blue-50 active:border-blue-100 dark:text-gray-200 dark:hover:text-white dark:hover:bg-gray-700/75 dark:active:border-gray-600"
            type="button" @click.prevent="linkClicked($event, true, index)">
          <span
              v-if="node.icon"
              class="flex-none flex items-center text-gray-400 group-hover:text-digicomm-500 dark:text-gray-500 dark:group-hover:text-gray-300"><font-awesome-icon
              :icon="node.icon"
              class="flex-none inline-block w-4 h-4"/></span>
          <span class="py-1 grow">{{ node.name }}</span>
          <span :ref="el => { refs['node-' + index + '-arrow'] = el }" :class="{
            '-rotate-90': subIsActive(node.subActivePaths)
          }" class="flex-none opacity-75">
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
        <div :ref="el => { refs['node-' + index + '-visibility'] = el }" :class="{
            'hidden': !subIsActive(node.subActivePaths)
          }" class="relative z-0 ml-7">

          <div v-for="(subnode, subindex) in node.sub" :key="`node-${index}-${subindex}`">
            <Link v-if="subnode.to" :class="{
            'border border-digicomm-100 bg-digicomm-50 dark:border-transparent dark:bg-gray-700/75': route().current(subnode.to),
            'space-x-2': subnode.icon
          }"
                  :href="route(subnode.to, subnode.params)"
                  class="group flex items-center px-2.5 text-sm font-medium rounded-lg text-gray-800 border border-transparent hover:text-gray-900 hover:bg-digicomm-50 active:border-digicomm-100 dark:text-white dark:hover:text-white dark:hover:bg-gray-700/75 dark:active:border-gray-600"
            >
        <span
            class="flex-none flex items-center text-gray-400 group-hover:text-digicomm-500 dark:text-gray-500 dark:group-hover:text-gray-300">
          <font-awesome-icon
              v-if="subnode.icon"
              :icon="subnode.icon" class="flex-none inline-block w-4 h-4"></font-awesome-icon>
        </span>
              <span class="py-1 grow">{{ subnode.name }}</span>

            </Link>


            <div v-else class="space-y-1">
              <button
                  class="w-full text-left group flex items-center space-x-2 px-2.5 text-sm font-medium rounded-lg text-gray-800 border border-transparent hover:text-gray-900 hover:bg-blue-50 active:border-blue-100 dark:text-gray-200 dark:hover:text-white dark:hover:bg-gray-700/75 dark:active:border-gray-600"
                  type="button" @click.prevent="linkClicked($event, true, index, subindex)">
          <span
              v-if="subnode.icon"
              class="flex-none flex items-center text-gray-400 group-hover:text-digicomm-500 dark:text-gray-500 dark:group-hover:text-gray-300"><font-awesome-icon
              :icon="subnode.icon"
              class="flex-none inline-block w-4 h-4"/></span>
                <span class="py-1 grow">{{ subnode.name }}</span>
                <span :ref="el => { refs['node-' + index + '-' + subindex + '-arrow'] = el }" :class="{
            '-rotate-90': subIsActive(subnode.subActivePaths)
          }" class="flex-none opacity-75">
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
              <div :ref="el => { refs['node-' + index + '-' + subindex + '-visibility'] = el }" :class="{
            'hidden': !subIsActive(subnode.subActivePaths)
          }" class="relative z-0 ml-7">

                <div v-for="(subsubnode, subsubindex) in subnode.sub" :key="`node-${index}-${subindex}-${subsubindex}`">
                  <Link v-if="subsubnode.to" :class="{
            'border border-digicomm-100 bg-digicomm-50 dark:border-transparent dark:bg-gray-700/75': route().current(subsubnode.to, subsubnode.params),
            'space-x-2': subsubnode.icon
          }"
                        :href="route(subsubnode.to, subsubnode.params)"
                        class="group flex items-center px-2.5 text-sm font-medium rounded-lg text-gray-800 border border-transparent hover:text-gray-900 hover:bg-digicomm-50 active:border-digicomm-100 dark:text-white dark:hover:text-white dark:hover:bg-gray-700/75 dark:active:border-gray-600"
                  >
        <span
            class="flex-none flex items-center text-gray-400 group-hover:text-digicomm-500 dark:text-gray-500 dark:group-hover:text-gray-300">
          <font-awesome-icon
              v-if="subsubnode.icon"
              :icon="subsubnode.icon" class="flex-none inline-block w-4 h-4"></font-awesome-icon>
        </span>
                    <span class="py-1 grow">{{ subsubnode.name }}</span>

                  </Link>
                  <div v-else>
                    {{ subsubnode.name }}
                  </div>
                </div>
              </div>

            </div>


          </div>
        </div>

      </div>
      <!-- END Submenu -->
      <div v-else-if="!node.to && !node.subActivePaths && node.heading"
           class="px-3 pt-5 pb-2 text-xs font-semibold uppercase tracking-wider text-gray-500">{{ node.name }}
      </div>


    </div>


  </nav>
</template>

<style scoped>

</style>