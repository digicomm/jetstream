<script lang="ts" setup>

import {computed, ref, toRef, useSlots, watch} from "vue";
import {TransitionChild, TransitionRoot} from "@headlessui/vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {isEmptySlot} from "@/js/Utils/dom";
import {useGeneratedId} from "@/js/Composables/useId";
import {DsTriggerableEvent} from "@/js/Utils";
import {onKeyStroke, useFocus, useVModel} from "@vueuse/core";
import type {Size} from "@/js/Types";

interface ModalProps {
  buttonSize?: Size
  autofocusOk?: boolean
  autofocusCancel?: boolean
  modelValue?: boolean
  autofocusClose?: boolean
  disableOk?: boolean
  disableCancel?: boolean
  enableClose?: boolean
  noCloseOnBackdrop?: boolean
  noCloseOnEsc?: boolean
  disableXButton?: boolean
  disableHeader?: boolean
  disableFooter?: boolean
  okButtonText?: string
  cancelButtonText?: string
  closeButtonText?: string
  icon?: Array
  iconVariant?: string
  id?: string
  panelId?: string
  size?: string
  position?: string
  title?: string
  rounded?: boolean | string
  tag?: string
}

const props = withDefaults(defineProps<ModalProps>(), {
  buttonSize: 'md',
  position: 'center',
  size: 'md',
  okButtonText: 'OK',
  cancelButtonText: 'Cancel',
  closeButtonText: 'Close',
  id: `digismart-modal-${useGeneratedId()}`,
  panelId: `digismart-modal-${useGeneratedId()}`,
  tag: 'div'
})
const emit = defineEmits<{
  'update:modelValue': [value: boolean]
  'backdrop': [value: DsTriggerableEvent]
  'show': [value: DsTriggerableEvent]
  'shown': [value: DsTriggerableEvent]
  'hide': [value: DsTriggerableEvent]
  'hidden': [value: DsTriggerableEvent]
  'hide-prevented': []
  'show-prevented': []
  'ok': [value: DsTriggerableEvent]
  'cancel': [value: DsTriggerableEvent]
  'close': [value: DsTriggerableEvent]
}>()
const modelValue = useVModel(props, "modelValue", emit, {passive: true})
const modalPositionClasses = {
  'top-left': ['justify-start', 'items-start'],
  'top-center': ['justify-center', 'items-start'],
  'top-right': ['justify-end', 'items-start'],

  'center-left': ['justify-start', 'items-center'],
  'center': ['justify-center', 'items-center'],
  'center-right': ['justify-end', 'items-center'],

  'bottom-left': ['justify-start', 'items-end'],
  'bottom-center': ['justify-center', 'items-end'],
  'bottom-right': ['justify-end', 'items-end'],
}






const buttonSizeClasses = {
  'xs': 'px-2 py-1 leading-5 text-sm',
  'sm': 'px-3 py-2 leading-5 text-sm',
  'md': 'px-4 py-2 leading-6',
  'lg': 'px-6 py-3 leading-6',
  'xl': 'px-8 py-4 leading-6',
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

const buttonOk = ref(null)
const buttonCancel = ref(null)
const buttonClose = ref(null)
const iconVariantClasses = {
  success: 'text-emerald-700 bg-emerald-100 dark:text-emerald-100 dark:bg-emerald-900 dark:bg-opacity-75',
  danger: 'text-emerald-700 bg-emerald-100 dark:text-emerald-100 dark:bg-emerald-900 dark:bg-opacity-75',
  warning: 'text-orange-700 bg-orange-100 dark:text-orange-100 dark:bg-orange-900 dark:bg-opacity-75',
  info: 'text-digicomm-700 bg-digicomm-100 dark:text-digicomm-100 dark:bg-digicomm-900 dark:bg-opacity-75',
  default: 'text-gray-700 bg-gray-100 dark:text-gray-300 dark:bg-gray-800'
}




const buildTriggerableEvent = (
    type: string,
    opts: Partial<DsTriggerableEvent> = {}
): DsTriggerableEvent =>
    new DsTriggerableEvent(type, {
      cancelable: false,
      target: element.value || null,
      relatedTarget: null,
      trigger: null,
      ...opts,
      componentId: computedId.value,
    })
const hide = (trigger = '') => {
  if(
      (trigger === 'backdrop' && props.noCloseOnBackdrop) ||
      (trigger === 'esc' && props.noCloseOnEsc)
  ) {
    emit('hide-prevented')
    return
  }
  const event = buildTriggerableEvent('hide', {cancelable: trigger !== '', trigger})

  if(trigger === 'ok' || trigger === 'cancel' || trigger === 'close') {
    emit(trigger, event)
  }
  emit('hide', event)
  if(event.defaultPrevented) {
    emit('hide-prevented')
    if(!modelValue.value) modelValue.value = false
  }


}

const showModal = () => {
  modelValue.value = true;
}

const computedDialogClasses = computed(() => [
  'flex',
  [modalPositionClasses[props.position]]
])

const computedPanelClasses = computed(() => [
  'w-full',
  [modalSizeClasses[props.size]],
  {
    [`rounded-${props.rounded}`]: props.rounded,
    'rounded-lg': props.rounded === false,
  }
])

const computedButtonClasses = computed(() => [
    [buttonSizeClasses[props.buttonSize]]
])

const computedHeaderClasses = computed(() => [
  {
    ['bg-gray-50 dark:bg-gray-700/50']: props.title || hasTitleSlot.value,
    ['bg-white dark:bg-gray-800']: !props.title && !hasTitleSlot.value,

  }
])


const onEscape = (e) => {
  if (modelValue.value && e.keyCode === 27) {
    hide('esc')
  }
}



const slots = useSlots()
const hasIconSlot = toRef(() => !isEmptySlot(slots.icon))
const hasTitleSlot = toRef(() => !isEmptySlot(slots.title))

const onAfterEnter = () => {
  emit('shown')
  if(props.autofocusClose) buttonClose.value.focus()
  if(props.autofocusCancel) buttonCancel.value.focus()
  if(props.autofocusOk) buttonOk.value.focus()
}

const onAfterLeave = () => {
  emit('hidden')
}

const showFn = () => {
  const event = buildTriggerableEvent('show', {cancelable: true})
  emit('show', event)
  if(event.defaultPrevented) {
    if(modelValue.value) modelValue.value = false
    emit('show-prevented')
    return
  }
  if(!modelValue.value) modelValue.value = true
}

document.addEventListener('keydown', onEscape)

defineExpose({

  id: props.id, show: showFn, hide
})

watch(modelValue, (newValue, oldValue) => {
  if(newValue === oldValue) return
  if(newValue === true) {
    showFn()
  } else {
    hide()
  }
})

</script>

<template>
  <div :id="props.id">
    <!-- Modal Container -->
    <TransitionRoot :show="modelValue" appear as="template">
      <div class="relative z-90">
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
          <div class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm"/>
        </TransitionChild>
        <!-- END Modal Backdrop -->

        <!-- Modal Dialog -->
        <div :class="computedDialogClasses" class="fixed inset-0 overflow-y-auto p-4 lg:p-8 "
             @click.self="hide('backdrop')">
          <TransitionChild
              as="template"
              enter="ease-out duration-200"
              enter-from="opacity-0 scale-125"
              enter-to="opacity-100 scale-100"
              leave="ease-in duration-100"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-125"
              @after-enter="onAfterEnter"
              @after-leave="onAfterLeave"
          >
            <div :id="panelId" :class="computedPanelClasses" class="w-full flex flex-col shadow-sm overflow-hidden bg-white dark:text-gray-100 dark:bg-gray-800" @keydown.esc="() => {console.log('escape')}">
              <div class="flex justify-between items-center px-3 py-2" :class="computedHeaderClasses" v-if="!disableHeader">
                <h3 class="font-medium">
                  <div v-if="!hasTitleSlot && title !== undefined">
                    {{ title}}
                  </div>
                  <slot name="title" v-else>&nbsp;</slot>
                </h3>
                <div v-if="!disableXButton" class="-my-4">
                  <button
                      class="inline-flex justify-center items-center space-x-2 border font-semibold rounded-lg px-1 py-0.5 leading-5 text-sm border-transparent text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:border-gray-200 active:shadow-none dark:border-transparent dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700"
                      type="button"
                      @click="hide('x')" v-if="!disableXButton"
                  >
                    <font-awesome-icon :icon="['far','times']"></font-awesome-icon>
                  </button>
                </div>
              </div>
              <div class="px-5 py-7 grow flex space-x-5">

                  <div
                      class="w-14 h-14 flex-none flex items-center justify-center rounded-full" :class="{
                    [iconVariantClasses[props.iconVariant]]: props.iconVariant,
                    [iconVariantClasses['default']]: !props.iconVariant,
                      }" v-if="icon && !hasIconSlot">
                    <font-awesome-icon :icon="icon" class="h-6 w-6"></font-awesome-icon>
                  </div>
                <slot name="icon" v-else>
                </slot>

                <div>
                  <slot/>
                </div>
              </div>
              <div class="text-right space-x-2 py-2 px-2 bg-gray-50 dark:bg-gray-700/50" v-if="!disableFooter">
                <slot name="cancel-button" v-if="!disableCancel">
                  <button type="button" :class="computedButtonClasses" class="inline-flex justify-center items-center border font-semibold rounded-lg border-gray-200 bg-white text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700" @click="hide('cancel')" ref="buttonCancel">
                    {{ cancelButtonText }}
                  </button>
                </slot>
                <slot name="ok-button" v-if="!disableOk">
                  <button type="button" :class="computedButtonClasses"  class="inline-flex justify-center items-center border font-semibold rounded-lg border-digicomm-700 bg-digicomm-700 text-white hover:text-white hover:bg-digicomm-600 hover:border-digicomm-600 focus:ring focus:ring-digicomm-400 focus:ring-opacity-50 active:bg-digicomm-700 active:border-digicomm-700 dark:focus:ring-digicomm-400 dark:focus:ring-opacity-90" @click="hide('ok')" ref="buttonOk">
                    {{ okButtonText }}
                  </button>
                </slot>
              </div>
            </div>
          </TransitionChild>
        </div>
        <!-- END Modal Dialog -->
      </div>
    </TransitionRoot>
    <!-- END Modal Container -->
  </div>
  <!-- END Modals: Confirmation -->

</template>

<style scoped>

</style>