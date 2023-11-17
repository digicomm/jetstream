<script lang="ts" setup>
import {computed} from "vue";

const props = withDefaults(
    defineProps<
        {
          tag?: string
          bgClass?: string
          containerClass?: string
          p?: string | number
          pX?: string | number
          pY?: string | number
          pT?: string | number
          pB?: string | number
          pL?: string | number
          pR?: string | number
        }
    >(),
    {
      tag: 'div',
      bgClass: null,
      containerClass: null,
      p: '3',
      pX: null,
      pY: null,
      pT: null,
      pB: null,
      pL: null,
      pR: null,
    }
)

const computedClasses = computed(() => [
  [props.bgClass],
  [props.containerClass],
  {
    [`p-${props.p}`]: props.p !== null,
    [`px-${props.pX}`]: props.pX !== null,
    [`py-${props.pY}`]: props.pY !== null,
    [`pt-${props.pT}`]: props.pT !== null,
    [`pb-${props.pB}`]: props.pB !== null,
    [`pl-${props.pL}`]: props.pL !== null,
    [`pr-${props.pR}`]: props.pR !== null,
  },
])

defineSlots<{
  default?: (props: Record<string, never>) => any
}>()
</script>

<template>
  <!-- Card Body -->
  <component :is="tag" class="grow" :class="computedClasses">
    <slot>
    </slot>
  </component>
  <!-- END Card Body -->
</template>