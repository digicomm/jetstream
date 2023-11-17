<script lang="ts" setup>

import {computed} from "vue";
import DLabel from "@/js/Components/DLabel.vue";
import type {Booleanish, InputType, Size} from "@/js/Types";
import DFormInput from "@/js/Components/DForm/DFormInput.vue";

const props = withDefaults(defineProps<{
  tag?: string
  space?: number | string
  type?: InputType
  horizontal?: boolean
  inputClass?: string
  inputId?: string
  label?: string
  labelFor?: string
  labelClass?: string
  size?: Size
}>(), {
  tag: 'div',
  space: 1,
  type: 'text',
  horizontal: false,
  inputClass: undefined,
  inputId: undefined,
  label: undefined,
  labelFor: undefined,
  labelClass: undefined,
})

const computedClasses = computed(() => [
  `space-y-${props.space}`,
  {
    ['md:space-y-0 md:flex md:items-center md:space-x-2']: props.horizontal
  }
])
const computedInputClass = computed(() => {
  if (props.inputClass !== undefined)
    return props.inputClass
})
</script>

<template>
  <component :is="tag" :class="computedClasses">
    <slot/>
  </component>
</template>