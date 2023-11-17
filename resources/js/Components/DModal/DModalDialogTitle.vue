<script setup lang="ts">
import {useGeneratedId} from "@/js/Composables/useId";
import {Dialog, DialogPanel, TransitionChild, TransitionRoot} from "@headlessui/vue";
import {ref} from "vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

interface ModalProps {
  noCloseOnBackdrop?: boolean
  noCloseOnEsc?: boolean
  id?: string
  size?: string
  as?: string
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
  id: `digismart-dialog-title-${useGeneratedId()}`,
  as: 'h2'
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
  console.log('test')
  console.log(event)
}

</script>

<template>
  <component :is="as" :id="props.id" class="flex justify-between items-center py-4 px-5 bg-gray-50 dark:bg-gray-700/50">
      <h3 class="font-medium">
        <slot />
      </h3>
      <div class="-my-4">
        <button
            @click="closeModal"
            type="button"
            class="inline-flex justify-center items-center space-x-2 border font-semibold rounded-lg px-3 py-2 leading-5 text-sm border-transparent text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:border-gray-200 active:shadow-none dark:border-transparent dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700"
        >
          <font-awesome-icon :icon="['far','times']"></font-awesome-icon>
        </button>
      </div>


  </component>

</template>

<style scoped>

</style>