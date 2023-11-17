<script setup lang="ts">
import {useGeneratedId} from "@/js/Composables/useId";
import {Dialog, DialogPanel, TransitionChild, TransitionRoot} from "@headlessui/vue";
import {ref} from "vue";
import DModalDialog from "@/js/Components/DModal/DModalDialog.vue";
import DModalDialogPanel from "@/js/Components/DModal/DModalDialogPanel.vue";
import DModalDialogTitle from "@/js/Components/DModal/DModalDialogTitle.vue";

interface ModalProps {
  noCloseOnBackdrop?: boolean
  noCloseOnEsc?: boolean
  id?: string
  size?: string
  tag?: string
}

const modalSizeClasses = {
  'xs': 'sm:max-w-xs',
  'sm': 'sm:max-w-sm',
  'md': 'sm:max-w-md',
  'lg': 'sm:max-w-lg',
  'xl': 'sm:max-w-xl',
  '2xl': 'sm:max-w-2xl',
  '3xl': 'sm:max-w-3xl',
  '4xl': 'sm:max-w-4xl',
  '5xl': 'sm:max-w-5xl',
  '6xl': 'sm:max-w-6xl',
  '7xl': 'sm:max-w-7xl',
}



const props = withDefaults(defineProps<ModalProps>(), {
  noCloseOnBackdrop: false,
  noCloseOnEsc: false,
  size: '2xl',
  id: `digismart-modal-${useGeneratedId()}`,
  tag: 'div'
})
const emit = defineEmits(['ok','cancel','close','show','shown','hide','hidden','hide-prevented','show-prevented'])

defineExpose({

  id: props.id
})
const isOpen = ref(true);

const closeModal = () => {
  isOpen.value = false;
}

const openModal = () => {
  isOpen.value = true;
}
function logToConsole(event) {
  console.log(event)
}

</script>

<template>
  <!-- Modals: Confirmation -->
  <component :is="tag">
    <!-- Placeholder -->
    <div class="flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300 py-40 dark:border-gray-700">
      <!-- Modal Toggle Button -->
      <button
          @click="openModal"
          type="button"
          class="inline-flex justify-center items-center space-x-2 border font-semibold rounded-lg px-4 py-2 leading-6 border-gray-200 bg-white text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700"
      >
        Delete File
      </button>
      <!-- END Modal Toggle Button -->
    </div>
    <!-- END Placeholder -->

    <!-- Modal Container -->
    <TransitionRoot appear :show="isOpen" as="template">
      <DModalDialog as="div" class="relative z-90">
        <!-- Modal Backdrop -->
        <TransitionChild
            as="template"
            enter="ease-out duration-200"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="ease-in duration-100"
            leave-from="opacity-100"
            leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm" @click.self="logToConsole('backdrop')"/>
        </TransitionChild>
        <!-- END Modal Backdrop -->

        <!-- Modal Dialog -->
        <div class="fixed inset-0 overflow-y-auto p-4 lg:p-8" @click.self="logToConsole('actual dialog')">
          <TransitionChild
              as="template"
              enter="ease-out duration-200"
              enter-from="opacity-0 scale-125"
              enter-to="opacity-100 scale-100"
              leave="ease-in duration-100"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-125"
          >
            <DModalDialogPanel
                class="w-full max-w-md mx-auto flex flex-col rounded-lg shadow-sm bg-white overflow-hidden dark:text-gray-100 dark:bg-gray-800"
            >
              <DModalDialogTitle>Test Title</DModalDialogTitle>
              <div class="px-5 py-7 grow flex space-x-5">
                <div class="w-14 h-14 flex-none flex items-center justify-center rounded-full text-rose-500 bg-rose-100 dark:text-rose-300 dark:bg-rose-700/50">
                  <svg class="hi-outline hi-shield-exclamation inline-block w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z"/></svg>
                </div>
                <div>
                  <h4 class="text-lg font-bold mb-1">
                    File Deletion
                  </h4>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Are you sure you want to completely remove <span class="font-semibold">Research.docx</span> from your drive?
                  </p>
                </div>
              </div>
              <div class="text-right space-x-2 py-4 px-5 bg-gray-50 dark:bg-gray-700/50">
                <button
                    @click="closeModal"
                    type="button"
                    class="inline-flex justify-center items-center space-x-2 border font-semibold rounded-lg px-3 py-2 leading-5 text-sm border-gray-200 bg-white text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700"
                >
                  Cancel
                </button>
                <button
                    @click="closeModal"
                    type="button"
                    class="inline-flex justify-center items-center space-x-2 border font-semibold rounded-lg px-3 py-2 leading-5 text-sm border-rose-700 bg-rose-700 text-white hover:text-white hover:bg-rose-600 hover:border-rose-600 focus:ring focus:ring-rose-400 focus:ring-opacity-50 active:bg-rose-700 active:border-rose-700 dark:focus:ring-rose-400 dark:focus:ring-opacity-90"
                >
                  Yes, delete file
                </button>
              </div>
            </DModalDialogPanel>
          </TransitionChild>
        </div>
        <!-- END Modal Dialog -->
      </DModalDialog>
    </TransitionRoot>
    <!-- END Modal Container -->
  </component>
  <!-- END Modals: Confirmation -->




</template>

<style scoped>

</style>
