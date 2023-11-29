<script lang="ts" setup>
import {computed, ref} from 'vue'
import {useBooleanish, useFormInput} from '@/js/Composables'
import type {CommonInputProps} from '@/js/Composables/useFormInput'
import type {InputType} from '@/js/Types'
import DLabel from "@/js/Components/DLabel.vue";
import InputError from "@/js/Components/InputError.vue";
import useInputSizeClass from "@/js/Composables/useInputSizeClass";
import DInputError from "@/js/Components/DInputError.vue";
import {upperCase} from "lodash";
import {onKeyStroke} from "@vueuse/core";

const props = withDefaults(
    defineProps<
        {
          max?: string | number
          min?: string | number
          step?: string | number
          horizontal?: boolean
          type?: InputType
          label?: string
          labelClass?: string
          inputClass?: string
          autofocus?: boolean
          vError?: boolean
          vErrorMessage?: Array
          formError?: string
          uppercase?: boolean
        } & CommonInputProps
    >(),
    {

      vError: false,
      vErrorMessage: undefined,
      horizontal: false,
      formError: undefined,
      label: undefined,
      labelClass: undefined,
      inputClass: undefined,
      max: undefined,
      min: undefined,
      step: undefined,
      type: 'text',

      // CommonInputProps
      ariaInvalid: undefined,
      autocomplete: undefined,
      autofocus: false,
      debounce: 0,
      debounceMaxWait: undefined,
      autofocus: false,
      disabled: false,
      form: undefined,
      formatter: undefined,
      id: undefined,
      lazy: false,
      lazyFormatter: false,
      list: undefined,
      modelValue: '',
      name: undefined,
      number: false,
      placeholder: undefined,
      plaintext: false,
      readonly: false,
      required: false,
      size: 'md',
      state: null,
      trim: false,
    }
)

const emit = defineEmits<{
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  'update:modelValue': [val: any]
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  'change': [val: any]
  'blur': [val: FocusEvent]
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  'input': [val: any]
}>()

const {input, computedId, computedAriaInvalid, onInput, onChange, onBlur, focus, blur} =
    useFormInput(props, emit)

const disabledBoolean = useBooleanish(() => props.disabled)
const requiredBoolean = useBooleanish(() => props.required)
const readonlyBoolean = useBooleanish(() => props.readonly)
const plaintextBoolean = useBooleanish(() => props.plaintext)

const sizeClass = useInputSizeClass(props.size)
const isHighlighted = ref(false)

const computedLabelClasses = computed(() => {
  return [
      props.labelClass,
    {
      'md:w-1/3 flex-none': props.horizontal,

    }
  ]
})

const computedClasses = computed(() => {
  return [
    'w-full block',
    sizeClass.value,
      props.inputClass,
    {
      'placeholder-gray-500 rounded-lg border-gray-200 focus:border-digicomm-500 focus:ring focus:ring-digicomm-500 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:focus:border-digicomm-500 dark:placeholder-gray-400': (!props.plaintext),
      'text-red-600 placeholder-red-400 border-red-400 focus:border-red-500 focus:ring focus:ring-red-500 focus:ring-opacity-50 dark:border-red-400 dark:text-red-400 dark:placeholder-red-400': props.vError,
      'bg-gray-100/75 dark:bg-gray-900/50': disabledBoolean.value,
      'uppercase': props.uppercase
    },
  ]
})


defineExpose({
  input,
  focus,
  blur,

})
</script>

<template>
  <DLabel v-if="label" :class="computedLabelClasses" :for="computedId" :size="size">{{ label }}</DLabel>
  <input
      :id="computedId"
      ref="input"
      :aria-autocomplete="autocomplete"
      :aria-required="requiredBoolean || undefined"
      :autocomplete="autocomplete === 'none' ? 'off' : autocomplete"
      :autofocus="autofocus"
      :class="computedClasses"
      :disabled="disabledBoolean"
      :form="form || undefined"
      :list="type !== 'password' ? list : undefined"
      :max="max"
      :min="min"
      :name="name || undefined"
      :placeholder="placeholder"
      :readonly="readonlyBoolean || plaintextBoolean"
      :required="requiredBoolean || undefined"
      :step="step"
      :type="type"
      @blur="onBlur($event)"
      @change="onChange($event)"
      @input="onInput($event)"
      v-bind="$attrs"
  />
  <DInputError :v-messages="vErrorMessage" :form-message="formError"/>

</template>

<style scoped>

</style>