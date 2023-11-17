<script setup lang="ts">
import {useGeneratedId} from "@/js/Composables/useId";
import {Dialog, DialogPanel, TransitionChild, TransitionRoot} from "@headlessui/vue";
import {ref} from "vue";

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
  id: `digismart-modal-${useGeneratedId()}`,
  as: 'div'
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
  <component :is="as" :id="props.id">
    <slot />
  </component>

</template>

<style scoped>

</style>