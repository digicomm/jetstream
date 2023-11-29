<script lang="ts" setup>
import {Link} from "@inertiajs/vue3";
import {onBeforeUpdate, reactive, ref} from "vue";
import {Cog6ToothIcon} from "@heroicons/vue/24/outline";
import {FontAwesomeLayers} from "@fortawesome/vue-fontawesome";

const list = reactive([1])
const refs = ref({})


const props = defineProps({
  nodes: {
    type: Array,
    description: "The nodes of the navigation",
  },
  subMenu: {
    type: Boolean,
    description: "If true, a submenu will be rendered",
  },
  miniSidebar: {
    type: Boolean,
    description: "miniSidebar is open"
  }
})

function linkClicked(e, submenu, index, subindex) {
  if (submenu) {
    if (!subindex) {

      for (const [key, value] of Object.entries(refs.value)) {
        if (!key.includes('-' + index + '-') && key.includes('-fa-plus')) {
          refs.value[key].$el.classList.add('fa-rotate-90')
        }

        if (!key.includes('-' + index + '-') && key.includes('-visibility')) {
          refs.value[key].classList.add('hidden')
        }
      }
      refs.value['node-' + index + '-fa-plus'].$el.classList.toggle('fa-rotate-90')
      refs.value['node-' + index + '-visibility'].classList.toggle('hidden')
    } else {

      refs.value['node-' + index + '-' + subindex + '-fa-plus'].$el.classList.toggle('fa-rotate-90')
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
  <nav class="flex flex-1 flex-col whitespace-nowrap">
    <ul class="flex flex-1 flex-col gap-y-7" role="list">
      <li class="space-y-1">
        <ul  class="-mx-2 space-y-1" role="list">
          <li v-for="(node, index) in nodes" :key="`node-${index}`">
          <!-- Regular Link -->
            <Link v-if="node.to && !node.subActivePaths"
                :class="{
              'text-white bg-digicomm-600/50 dark:bg-gray-800': route().current(node.to),
              'text-digicomm-200 dark:text-gray-400 dark:bg-gray-900 hover:bg-digicomm-600/25 hover:dark:bg-gray-800': !route().current(node.to),
              'space-x-2': node.icon
            }"
                :href="route(node.to)"
                class="group flex gap-x-3 rounded-md px-2 py-1 text-sm font-semibold"
            >
        <span v-if="node.icon" class="flex-none flex items-center group-hover:text-white">
          <font-awesome-icon :icon="node.icon" class="flex-none inline-block w-4 h-4"/>
        </span>
              <span :class="{'opacity-0': miniSidebar}" class="grow group-hover:text-white">{{ node.name }}</span>
            </Link>
          <!-- END Regular Link -->


          <!-- Heading -->
            <div v-else-if="node.heading" class="text-xs font-semibold leading-6 text-digicomm-200 dark:text-gray-400 uppercase tracking-wider">{{ node.name }}</div>

          <!-- END Heading -->

          <!-- Submenu -->
          <div v-else>
              <a
                  :class="{
              'text-white bg-digicomm-600/50 dark:bg-gray-800': subIsActive(node.subActivePaths),
              'text-digicomm-200 dark:text-gray-400 dark:bg-gray-900 hover:bg-digicomm-600/25 hover:dark:bg-gray-800': !subIsActive(node.subActivePaths),
              'space-x-2': node.icon
            }"
                  href="#"
                  @click.prevent="linkClicked($event, true, index)"
                  class="group flex gap-x-3 rounded-md px-2 py-1 text-sm font-semibold"
              >
        <span v-if="node.icon" class="flex-none flex items-center group-hover:text-white">
          <font-awesome-icon :icon="node.icon" class="flex-none inline-block w-4 h-4"/>
        </span>
                <span :class="{'opacity-0': miniSidebar}" class="grow group-hover:text-white">{{ node.name }}</span>
                <font-awesome-layers>
                  <font-awesome-icon :ref="el => { refs['node-' + index + '-fa'] = el }"
                                     :icon="['far','minus']" fixed-width
                                     class="flex-none inline-block w-4 h-4 group-hover:text-white"/>
                  <font-awesome-icon :ref="el => { refs['node-' + index + '-fa-plus'] = el }"
                                     :icon="['far','minus']" fixed-width rotation="90"
                                     class="flex-none inline-block w-4 h-4 group-hover:text-white"/>
                </font-awesome-layers>

              </a>
            <ul :ref="el => { refs['node-' + index + '-visibility'] = el }" :class="{
            'hidden': !subIsActive(node.subActivePaths)
          }" class="relative z-0 ml-9">

              <li v-for="(subnode, subindex) in node.sub" :key="`node-${index}-${subindex}`">
                <Link v-if="subnode.to"
                      :href="route(subnode.to, subnode.params)"
                      :class="{
              'text-white bg-digicomm-600/50 dark:bg-gray-800': route().current(subnode.to),
              'text-digicomm-200 dark:text-gray-400 dark:bg-gray-900 hover:text-white hover:bg-digicomm-600/25 hover:dark:bg-gray-800': !route().current(subnode.to),
            }"
                      class="group flex gap-x-1 rounded-md px-2 py-1 text-sm font-normal"
                >
                  <span class="grow">{{ subnode.name }}</span>

                </Link>


                  <a v-else
                      :class="{
              'text-white bg-digicomm-600/50 dark:bg-gray-800': subIsActive(node.subActivePaths),
              'text-digicomm-200 dark:text-gray-400 dark:bg-gray-900 hover:text-white hover:bg-digicomm-600/25 hover:dark:bg-gray-800': !subIsActive(node.subActivePaths),

            }"
                      href="#"
                      @click.prevent="linkClicked($event, true, index, subindex)"
                      class="group flex gap-x-1 rounded-md px-2 py-1 text-sm font-normal"
                  >
                    <span :class="{'opacity-0': miniSidebar}" class="grow group-hover:text-white">{{ node.name }}</span>
                    <font-awesome-layers>
                      <font-awesome-icon :ref="el => { refs['node-' + index + '-' + subindex + '-fa'] = el }"
                                         :icon="['far','minus']" fixed-width
                                         class="flex-none inline-block w-4 h-4 group-hover:text-white"/>
                      <font-awesome-icon :ref="el => { refs['node-' + index + '-' + subindex + '-fa-plus'] = el }"
                                         :icon="['far','minus']" fixed-width rotation="90"
                                         class="flex-none inline-block w-4 h-4 group-hover:text-white"/>
                    </font-awesome-layers>

                  </a>

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
                  <ul :ref="el => { refs['node-' + index + '-' + subindex + '-visibility'] = el }" :class="{
            'hidden': !subIsActive(subnode.subActivePaths)
          }" class="relative z-0 ml-3">


                    <li v-for="(subsubnode, subsubindex) in subnode.sub" :key="`node-${index}-${subindex}-${subsubindex}`">
                      <Link v-if="subsubnode.to"
                            :href="route(subsubnode.to, subsubnode.params)"
                            :class="{
              'text-white bg-digicomm-600/50 dark:bg-gray-800': route().current(subsubnode.to),
              'text-digicomm-200 dark:text-gray-400 dark:bg-gray-900 hover:text-white hover:bg-digicomm-600/25 hover:dark:bg-gray-800': !route().current(subsubnode.to),
            }"
                            class="group flex gap-x-3 rounded-md px-2 py-1 text-sm font-light"
                      >
                        <span class="grow">{{ subsubnode.name }}</span>

                      </Link>
                      <div v-else>
                        {{ subsubnode.name }}
                      </div>
                    </li>
                  </ul>



              </li>
            </ul>

          </div>
          <!-- END Submenu -->


          </li>

        </ul>
      </li>
    </ul>
  </nav>


</template>

<style scoped>

</style>