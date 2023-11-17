<script setup>
import {computed, onMounted, ref} from 'vue';

const props = defineProps({
  modelValue: String,
  size: {type: String, default: 'md'},
  label: {type: String, default: undefined},
  labelFor: {type: String, default: undefined},
  formGroup: Boolean,
  type: {type: String, default: 'text'},
  id: {type: String, default: undefined},
  autoComplete: Boolean,
  inputClass: {type: String, default: undefined},
  required: Boolean,
  readonly: Boolean,
  message: {type: String, default: undefined},
  cols: {type: String, default: '10'}
})

defineEmits(['update:modelValue'])

const formGroupBoolean = props.formGroup
const input = ref(null);

const sizeLookup = {
  xs: 'text-xs',
  sm: 'text-sm',
  md: 'text-base',
  lg: 'text-xl',
  xl: 'text-3xl'
}

const computedClasses = computed(() => [
  sizeLookup[props.size],
  {
    [`${props.inputClass}`]: props.inputClass !== undefined
  }
])

const computedAriaAutocomplete = computed(() => {
  return props.autoComplete ? 'both' : 'none'
})

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    //input.value.focus();
  }
});

defineExpose({focus: () => input.value.focus()});
</script>

<template>
  <div v-if="label" :class="`col-span-${parseInt(props.cols)}`" role="group">
    <label :for="labelFor" class="block font-medium text-gray-700 dark:text-gray-300">
      <span v-if="label">{{ label }}</span>
      <span v-else><slot name="label"/></span>
    </label>
    <input
        :id="id"
        ref="input"
        :aria-autocomplete="computedAriaAutocomplete"
        :class="computedClasses"
        :readonly="readonly"
        :required="required"
        :value="modelValue"
        class="px-2.5 py-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
        @input="$emit('update:modelValue', $event.target.value)"
    >
    <p v-show="message" class="text-sm text-red-600 dark:text-red-400 mt-2">
      {{ message }}
    </p>
  </div>
  <input v-else
         :id="id"
         ref="input"
         :aria-autocomplete="computedAriaAutocomplete"
         :class="computedClasses"
         :readonly="readonly"
         :required="required"
         :value="modelValue"
         class="px-2.5 py-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
         @input="$emit('update:modelValue', $event.target.value)"
  >
</template>
