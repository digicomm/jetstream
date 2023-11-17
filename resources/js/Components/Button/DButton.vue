<script setup>
import {computed} from "vue";
import DSpinner from "@/js/Components/DSpinner.vue";

const props = defineProps({
  type: {
    type: String,
    required: false,
    default: 'button'
  },
  size: {
    type: String,
    required: false,
    default: 'md'
  },
  textSize: {
    type: String,
    required: false,
    default: undefined
  },
  uppercase: Boolean,
  variant: {
    type: String,
    required: false,
    default: 'simple'
  },
  loading: Boolean,
  pill: Boolean,
  square: Boolean,
  tag: {
    type: String,
    required: false,
    default: 'button'
  },
  loadingText: {
    type: String,
    required: false,
    default: 'Loading...'
  },
  to: {
    type: String,
    default: undefined
  },
  href: {
    type: String,
    default: undefined
  },
  processing: Boolean,
  loadingFill: Boolean,
  block: Boolean,
})

const variantLookup = {
  primary: 'border-blue-700 bg-blue-700 text-white hover:text-white hover:bg-blue-600 hover:border-blue-600 focus:ring focus:ring-blue-400 focus:ring-opacity-50 active:bg-blue-700 active:border-blue-700 dark:focus:ring-blue-400 dark:focus:ring-opacity-90',
  digicomm: 'border-digicomm-700 bg-digicomm-700 text-white hover:text-white hover:bg-digicomm-600 hover:border-digicomm-600 focus:ring focus:ring-digicomm-400 focus:ring-opacity-50 active:bg-digicomm-700 active:border-digicomm-700 dark:focus:ring-digicomm-400 dark:focus:ring-opacity-90',
  secondary: 'border-blue-200 bg-blue-100 text-blue-800 hover:border-blue-300 hover:text-blue-900 hover:shadow-sm focus:ring focus:ring-blue-300 focus:ring-opacity-25 active:border-blue-200 active:shadow-none dark:border-blue-200 dark:bg-blue-200 dark:hover:border-blue-300 dark:hover:bg-blue-300 dark:focus:ring-blue-500 dark:focus:ring-opacity-50 dark:active:border-blue-200 dark:active:bg-blue-200',
  simple: 'border-gray-300 bg-white text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700',
}

const sizeLookup = {
  xs: 'px-2 py-1',
  sm: 'px-3 py-2',
  md: 'px-4 py-2',
  lg: 'px-6 py-3',
  xl: 'px-8 py-4'
}

const pillBoolean = props.pill
const squareBoolean = props.square
const uppercaseBoolean = props.uppercase
const blockBoolean = props.block
const isButton = computed(
    () => props.tag === 'button' && props.href === undefined && props.to === undefined
)
const loadingBoolean = props.loading
const loadingFillBoolean = props.loadingFill

const computedClasses = computed(() => [
  sizeLookup[props.size], variantLookup[props.variant],
  {
    [`text-${props.size}`]: props.textSize === undefined,
    [`text-${props.textSize}`]: props.textSize !== undefined,
    'uppercase': uppercaseBoolean,
    'rounded-pill': pillBoolean,
    'rounded-md': !squareBoolean,
    'opacity-25': loadingBoolean,
    'w-full': blockBoolean,
  }
])
</script>

<template>
  <button
      :class="computedClasses"
      :type="isButton ? type : null"
      class="inline-flex justify-center items-center space-x-2 border font-semibold transition ease-in-out duration-150">
    <template v-if="loadingBoolean">
      <slot name="loading">
        <template v-if="!loadingFillBoolean">
          {{ loadingText }}
        </template>
        <slot name="loading-spinner">
          <DSpinner :label="loadingFillBoolean ? loadingText : undefined" :small="size !== 'lg'"/>
        </slot>
      </slot>
    </template>
    <template v-else>
      <slot/>
    </template>
  </button>
</template>