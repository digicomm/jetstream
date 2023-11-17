<script lang="ts" setup>
//import {Size} from "@/js/Types";
import {useGeneratedId} from "@/js/Composables/useId";
import {computed, type InjectionKey, reactive, type Ref, ref, type RendererElement, toRef, useSlots, watch} from "vue";
import {useId} from "@/js/Composables";
import {onKeyStroke, useFocus, useVModel} from "@vueuse/core";
import {Dialog, TransitionChild, TransitionRoot} from "@headlessui/vue";
import {isEmptySlot} from "@/js/Utils/dom";
import {State, useOpenClosed} from "@/js/Internal/open-closed";
import {DsTriggerableEvent} from "@/js/Utils";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const panel = ref(null)
enum DialogStates {
  Open,
  Closed
}

interface StateDefinition {
  dialogState: Ref<DialogStates>

  titleId: Ref<string | null>
  panelRef: Ref<HTMLDivElement | null>

  setTitleId(id: string | null): void

  close(): void
}

let ready = ref(false)
let DialogContext = Symbol('DialogContext') as InjectionKey<StateDefinition>
let dialogState = computed(() => !ready.value ? DialogStates.Closed : open.value ? DialogStates.Open : DialogStates.Closed)
let usesOpenClosedState = useOpenClosed()
let open = computed(() => {
  if (props.open === Missing && usesOpenClosedState !== null) {
    return (usesOpenClosedState.value & State.Open) === State.open
  }
  return props.open
})

let enabled = computed(() => dialogState.value === DialogStates.Open)

interface ModalProps {
  autofocusButton?: string
  autofocusDisabled?: boolean
  buttonSize?: string
  cancelButtonText?: string
  closeButtonText?: string
  notification?: boolean
  notificationButtonText?: string
  cancelDisabled?: boolean
  cancelTitle?: string
  okDisabled?: boolean
  okTitle?: string
  okOnly?: boolean
  disableOk?: boolean
  enableClose?: boolean
  headerBgClass?: string
  headerPaddingClass?: string
  hideBackdrop?: boolean
  hideFooter?: boolean
  hideHeader?: boolean
  hideHeaderClose?: boolean
  bodyIcon?: boolean
  icon?: Array
  iconVariant?: string
  id?: string
  idPanel?: string
  idBackdrop?: string
  idPanelTitle?: string
  panelTitleClass?: string
  modelValue?: boolean
  noCloseOnBackdrop?: boolean
  noCloseOnEsc?: boolean
  okButtonText?: string
  open?: boolean | string
  position?: string
  busy?: boolean
  rounded?: boolean | string
  size?: string
  tag?: string
  title?: string
  teleportTo?: string | RendererElement | null | undefined
  teleportDisabled?: boolean
}

const props = withDefaults(defineProps<ModalProps>(), {
  buttonSize: 'md',
  position: 'center',
  cancelTitle: 'Cancel',
  headerBgClass: 'bg-gray-50 dark:bg-gray-700/50',
  headerPaddingClass: 'px-3 py-2',
  okTitle: 'Cancel',
  size: 'md',
  okButtonText: 'OK',
  cancelButtonText: 'Cancel',
  notificationButtonText: 'OK',
  open: 'missing',
  closeButtonText: 'Close',
  id: `digismart-modal-${useGeneratedId()}`,
  idBackdrop: `digismart-modal-${useGeneratedId()}`,
  idPanel: `digismart-modal-${useGeneratedId()}`,
  idPanelTitle: `ds-modal-${useGeneratedId()}`,
  tag: 'div'
})

const emit = defineEmits<{
  'update:modelValue': [value: boolean]
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

type SharedSlotsData = {
  cancel: () => void
  close: () => void
  hide: (trigger?: string) => void
  ok: () => void
  visible: boolean
}

defineSlots<{
  'header'?: (props: SharedSlotsData) => any
  'title'?: (props: SharedSlotsData) => any
  'header-title'?: (props: SharedSlotsData) => any
  'header-close'?: (props: Record<string, never>) => any
  'default'?: (props: SharedSlotsData) => any
  'footer'?: (props: SharedSlotsData) => any
  'cancel'?: (props: SharedSlotsData) => any
  'ok'?: (props: SharedSlotsData) => any
  'notification'?: (props: SharedSlotsData) => any
  'backdrop'?: (props: Record<string, never>) => any
  'panel-title'?: (props: SharedSlotsData) => any
  'iconbody'?: (props: SharedSlotsData) => any
}>()

const slots = useSlots()

const hasHeaderCloseSlot = toRef(() => !isEmptySlot(slots['header-close']))
const hasPanelTitleSlot = toRef(() => !isEmptySlot(slots['panel-title']))
const hasIconBodySlot = toRef(() => !isEmptySlot(slots['iconbody']))

const computedId = useId(() => props.id, 'modal')

const modelValue = useVModel(props, 'modelValue', emit, {passive: true})

const element = ref<HTMLElement | null>(null)
const okButton = ref<HTMLElement | null>(null)
const cancelButton = ref<HTMLElement | null>(null)
const closeButton = ref<HTMLElement | null>(null)
const notificationButton = ref<HTMLElement | null>(null)
const isActive = ref(modelValue.value)
const lazyLoadCompleted = ref(false)

onKeyStroke('Escape', (e) => {
  console.log(e.target)
})

onKeyStroke('Escape', () => {
  console.log('escape pressed')
  hide('esc')
}, {target: element})

const {focused: modalFocus} = useFocus(element, {initialValue: modelValue.value && props.autofocusButton === undefined})
const {focused: okButtonFocus} = useFocus(okButton, {initialValue: modelValue.value && props.autofocusButton === 'ok'})
const {focused: cancelButtonFocus} = useFocus(cancelButton, {initialValue: modelValue.value && props.autofocusButton === 'cancel'})
const {focused: closeButtonFocus} = useFocus(closeButton, {initialValue: modelValue.value && props.autofocusButton === 'close'})
const {focused: notificationButtonFocus} = useFocus(notificationButton, {initialValue: modelValue.value && props.autofocusButton === 'notification'})
const disableCancel = toRef(() => props.cancelDisabled || props.busy)
const disableOk = toRef(() => props.okDisabled || props.busy)

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
      componentId: computedId.value
    })

watch(modelValue, (newValue, oldValue) => {
  if (newValue === oldValue) return
  if (newValue === true) {
    showFn()
  } else {
    hide()
  }
})

const hide = (trigger = '') => {
  if (
      (trigger === 'backdrop' && props.noCloseOnBackdrop) ||
      (trigger === 'esc' && props.noCloseOnEsc)
  ) {
    emit('hide-prevented')
    return
  }
  const event = buildTriggerableEvent('hide', {cancelable: trigger !== '', trigger})

  switch (trigger) {
    case 'ok':
    case 'cancel':
    case 'close':
      emit(trigger, event)
      break;
  }
  emit('hide', event)

  if (event.defaultPrevented) {
    emit('hide-prevented')
    if (!modelValue.value) modelValue.value = true
    return
  }
  if (modelValue.value) modelValue.value = false
}

const showFn = () => {
  const event = buildTriggerableEvent('show', {cancelable: true})
  emit('show', event)
  if (event.defaultPrevented) {
    if (modelValue.value) modelValue.value = false
    emit('show-prevented')
    return
  }
  if (!modelValue.value) modelValue.value = true
}

const pickFocusItem = () => {
  if (!props.autofocusDisabled) {
    return props.autofocusButton === 'ok' ? (okButtonFocus.value = true) : props.autofocusButton === 'close' ? (closeButtonFocus.value = true) : props.autofocusButton === 'cancel' ? (cancelButtonFocus.value = true) : props.autofocusButton === 'notification' ? (notificationButtonFocus.value = true) : (modalFocus.value = true)

    //return focus
  }


}

const onBeforeEnter = () => showFn()
const onAfterEnter = () => {
  isActive.value = true
  pickFocusItem()
  emit('shown', buildTriggerableEvent('shown'))
}
const onLeave = () => {
  isActive.value = false
}

const onAfterLeave = () => {
  emit('hidden', buildTriggerableEvent('hidden'))
}

const sharedSlots: SharedSlotsData = reactive({
  cancel: () => {
    hide('cancel')
  },
  close: () => {
    hide('close')
  },
  hide,
  ok: () => {
    hide('ok')
  },
  visible: modelValue.value,
})

defineExpose({
  hide,
  show: showFn,
  id: computedId
})


const buttonSizeClasses = {
  'xs': 'px-2 py-1 leading-5 text-sm',
  'sm': 'px-3 py-2 leading-5 text-sm',
  'md': 'px-4 py-2 leading-6',
  'lg': 'px-6 py-3 leading-6',
  'xl': 'px-8 py-4 leading-6',
}
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

const iconVariantClasses = {
  success: 'text-emerald-700 bg-emerald-100 dark:text-emerald-100 dark:bg-emerald-900 dark:bg-opacity-75',
  danger: 'text-emerald-700 bg-emerald-100 dark:text-emerald-100 dark:bg-emerald-900 dark:bg-opacity-75',
  warning: 'text-orange-700 bg-orange-100 dark:text-orange-100 dark:bg-orange-900 dark:bg-opacity-75',
  info: 'text-digicomm-700 bg-digicomm-100 dark:text-digicomm-100 dark:bg-digicomm-900 dark:bg-opacity-75',
  default: 'text-gray-700 bg-gray-100 dark:text-gray-300 dark:bg-gray-800'
}

const computedDialogClasses = computed(() => [
  'fixed inset-0 overflow-y-auto p-4 lg:p-8 flex',
  [modalPositionClasses[props.position]]
])
const computedPanelClasses = computed(() => [
  'w-full mx-auto flex flex-col',
  'shadow-sm bg-white overflow-hidden dark:text-gray-100 dark:bg-gray-800',
  [modalSizeClasses[props.size]],
  {
    [`rounded-${props.rounded}`]: props.rounded,
    'rounded-lg': props.rounded === false,
  }
])
const computedHeaderClasses = computed(() => [
  'flex justify-between items-center',
  props.headerBgClass,
  props.headerPaddingClass
])
const computedIconClasses = computed(() => [
  'w-14 h-14 flex-none flex items-center justify-center rounded-full',
  {
    [iconVariantClasses[props.iconVariant]]: props.iconVariant,
    [iconVariantClasses['default']]: !props.iconVariant,
  }
])
onKeyStroke(
    'Escape',
    () => {
      hide('esc')
    },
    {target: element}
)

const computedButtonClasses = computed(() => [
  [buttonSizeClasses[props.buttonSize]]
])

const isOpen = ref(true)

function closeModal() {
  isOpen.value = false
}

function openModal() {
  isOpen.value = true
}

const backdropClick = (e) => {
  if(e.target.id === props.idBackdrop) hide('backdrop')
}
</script>

<template>
  <div class="fixed inset-0 flex items-center justify-center">
    <button
        class="rounded-md bg-black/20 px-4 py-2 text-sm font-medium text-white hover:bg-black/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
        type="button"
        @click="modelValue = !modelValue"
    >
      Open dialog
    </button>
  </div>
  <Teleport to="body">
    <TransitionRoot :show="modelValue" appear as="template">
      <div class="relative z-50" @close="closeModal">
        <!-- Modal Backdrop -->
        <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100"
            leave-to="opacity-0"
            @after-enter="onAfterEnter"
            @after-leave="onAfterLeave"
        >
          <div class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm" />
        </TransitionChild>
        <!-- END Modal Backdrop -->

        <div :id="idBackdrop" :class="computedDialogClasses" ref="element" @click="backdropClick">
          <div
              class="flex items-center justify-center p-0"
          >
          <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
          >
            <div :id="idPanel" :class="computedPanelClasses" ref="panel"
            >
              <slot v-if="!hideHeader" name="header">
                <div :class="computedHeaderClasses">
                  <h3 class="font-bold">
                    <slot name="header-title">&nbsp;</slot>
                  </h3>
                  <slot v-if="!hideHeaderClose" name="header-close">
                    <div class="-my-4">
                      <button
                          class="inline-flex justify-center items-center space-x-2 border font-semibold rounded-lg px-0 py-1 leading-5 text-sm border-transparent text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:border-gray-200 active:shadow-none dark:border-transparent dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700"
                          type="button" tabindex="-1"
                          @click="hide('close')"
                      >
                        <font-awesome-icon :icon="['far','times']" fixed-width></font-awesome-icon>
                      </button>
                    </div>
                  </slot>
                </div>
              </slot>
              <slot v-if="bodyIcon" name="bodyicon">
                <div class="px-5 py-7 grow flex space-x-5">
                  <div :class="computedIconClasses">
                    <slot name="content-icon">
                      <font-awesome-icon :icon="icon" class="h-6 w-6" fixed-width></font-awesome-icon>
                    </slot>
                  </div>
                  <div>
                    <h4 class="text-lg font-bold mb-1">
                      File Deletion
                    </h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                      Are you sure you want to completely remove <span class="font-semibold">Research.docx</span> from
                      your drive?
                    </p>
                  </div>
                </div>
              </slot>


              <slot name="footerbuttons">
                <div class="text-right space-x-2 px-3 py-2 bg-gray-50 dark:bg-gray-700/50">
                  <slot name="button-cancel">
                    <button
                        :class="computedButtonClasses"
                        class="inline-flex justify-center items-center space-x-2 border font-semibold rounded-lg border-gray-200 bg-white text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700"
                        type="button"
                        ref="cancelButton"
                        @click="closeModal"
                    >
                      {{ cancelButtonText }}
                    </button>
                  </slot>
                  <slot name="button-ok">
                    <button
                        :class="computedButtonClasses"
                        class="inline-flex justify-center items-center space-x-2 border font-semibold rounded-lg border-rose-700 bg-rose-700 text-white hover:text-white hover:bg-rose-600 hover:border-rose-600 focus:ring focus:ring-rose-400 focus:ring-opacity-50 active:bg-rose-700 active:border-rose-700 dark:focus:ring-rose-400 dark:focus:ring-opacity-90"
                        type="button"
                        ref="okButton"
                        @click="closeModal"
                    >
                      {{ okButtonText }}
                    </button>
                  </slot>
                </div>
              </slot>


            </div>
          </TransitionChild>
            </div>
        </div>
      </div>
    </TransitionRoot>
  </Teleport>

</template>

<style scoped>

</style>