<script setup lang="ts">
import {computed} from "vue";
import type {Size} from "@/js/Types";

const props = withDefaults(defineProps<{
  for?: string
  labelClass?: string
  size?: Size
}>(), {
  for: undefined,
  size: 'md',
})

const computedLabelClass = computed(() => [
  [`text-${props.size}`],
  {
    'font-medium': props.labelClass === undefined,
    [props.labelClass]: props.labelClass !== undefined
  }
  ])
</script>

<template>
<label :for="props.for" :class="computedLabelClass">
  <slot/>
</label>
</template>

<style scoped>

</style>