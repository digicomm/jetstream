<script lang="ts" setup>
import {computed, toRef, useSlots} from "vue";
import {isEmptySlot} from "@/js/Utils/dom";
import DCardHeader from "@/js/Components/DCard/DCardHeadFoot.vue";

const props = withDefaults(
    defineProps<{
      tag?: string
      containerClass?: string
      showOverflow?: boolean
    }
    >(),
    {
      tag: 'div',
      containerClass: undefined,
      showOverflow: false,
    }
)
const computedClass = computed(() => {
  return [
  {
   'overflow-hidden': !props.showOverflow,
    [props.containerClass]: props.containerClass !== undefined
  }
  ]
})
defineSlots<{
  default?: (props: Record<string, never>) => any
}>()
</script>

<template>
  <component :is="tag" class="flex flex-col rounded-lg shadow-sm bg-white dark:text-gray-100 dark:bg-gray-800" :class="computedClass">
    <slot></slot>
  </component>
</template>

<style scoped>

</style>