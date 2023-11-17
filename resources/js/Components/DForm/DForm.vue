<script lang="ts" setup>
import type {DFormProps} from "@/js/Types";
import {computed, ref} from "vue";
import useBooleanish from "@/js/Composables/useBooleanish";

const props = withDefaults(defineProps<DFormProps>(), {
  id: undefined,
  novalidate: false,
  inline: false,
  space: '6',
})

const emit = defineEmits<{
  submit: [value: Event]
}>()
const element = ref<HTMLFormElement | null>(null)

const novalidateBoolean = useBooleanish(() => props.novalidate)

defineSlots<{
  default?: (props: Record<string, never>) => any
}>()

const submitted = (e: Event) => {
  emit('submit', e)
}

const inlineBoolean = useBooleanish(() => props.inline)

const computedClasses = computed(() => [
  `space-y-${props.space}`,
  {
    'sm:space-y-0 sm:flex sm:items-center sm:space-x-2 dark:text-gray-100': inlineBoolean.value
  }

])
defineExpose({
  element,
})
</script>

<template>
  <form :id="id" ref="element" :class="computedClasses" :novalidate="novalidateBoolean" @submit.prevent="submitted">
    <slot/>
  </form>
</template>