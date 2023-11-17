<script lang="ts" setup>
import {computed} from "vue";
import type {AlignmentVertical} from "@/js/Types/AlignmentVertical";
import type {AlignmentJustifyContent} from "@/js/Types/AlignmentJustifyContent";
import type {AlignmentContent} from "@/js/Types/AlignmentContent";
import type {Booleanish} from "@/js/Types/Booleanish";
import useBooleanish from "@/js/Composables/useBooleanish";
import useAlignment from "@/js/Composables/useAlignment";

const props = withDefaults(defineProps<{
      tag?: string,
      gap?: string,
      gapX?: string,
      gapY?: string,
      noGap?: Booleanish,
      alignV?: AlignmentVertical,
      alignH?: AlignmentJustifyContent,
      alignC?: AlignmentContent,
      cols?: string | number,
      sm?: string | number,
      md?: string | number,
      lg?: string | number,
      xl?: string | number,
      xxl?: string | number,
    }>(),
    {
      tag: 'div',
      gap: '4',
      gapX: null,
      gapY: null,
      noGap: false,
      alignV: null,
      alignH: null,
      alignC: null,
      cols: '1',
      sm: null,
      md: null,
      lg: null,
      xl: null,
      xxl: null,
    })
const noGapBoolean = useBooleanish(() => props.noGap)
const alignment = useAlignment(() => props.alignH)
const computedClasses = computed(() => [
  'grid',
  [`grid-cols-${props.cols}`],
  {
    [`sm:grid-cols-${props.sm}`]: props.sm !== null,
    [`md:grid-cols-${props.md}`]: props.md !== null,
    [`lg:grid-cols-${props.lg}`]: props.lg !== null,
    [`xl:grid-cols-${props.xl}`]: props.xl !== null,
    [`2xl:grid-cols-${props.xxl}`]: props.xxl !== null,
    [`gap-${props.gap}`]: props.gapX === null && props.gapY === null,
    [`gap-x-${props.gapX}`]: props.gapX !== null,
    [`gap-y-${props.gapY}`]: props.gapY !== null,
    'gap-0': noGapBoolean.value,
    [`gap-${props.gap}`]: props.gap,
    [`items-${props.alignV}`]: props.alignV !== null,
    [alignment.value]: props.alignH !== null,
    [`content-${props.alignC}`]: props.alignC !== null
  },
])

defineSlots<{
  default?: (props: Record<string, never>) => any
}>()
</script>

<template>
  <component :is="tag" :class="computedClasses">
    <slot/>
  </component>
</template>

<style scoped>

</style>