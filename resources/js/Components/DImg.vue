<script lang="ts" setup>
import type {DImgProps} from "@/js/Types";
import useBooleanish from "@/js/Composables/useBooleanish";
import {useToNumber} from "@vueuse/core";
import {computed, toRef} from "vue";

const props = withDefaults(defineProps<DImgProps>(), {
  sizes: undefined,
  src: undefined,
  srcset: undefined,
  width: undefined,
  height: undefined,
  blank: false,
  lazy: false,
  blankColor: 'transparent',
  block: false,
  center: false,
  fluid: false,
  fluidGrow: false,
  end: false,
  start: false,
  rounded: false,
  thumbnail: false,
  imgClass: '',
})

const BLANK_TEMPLATE =
    '<svg width="%{w}" height="%{h}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %{w} %{h}" preserveAspectRatio="none">' +
    '<rect width="100%" height="100%" style="fill:%{f};"></rect>' +
    '</svg>'

const lazyBoolean = useBooleanish(() => props.lazy)
const blankBoolean = useBooleanish(() => props.blank)
const blockBoolean = useBooleanish(() => props.block)
const centerBoolean = useBooleanish(() => props.center)
const fluidBoolean = useBooleanish(() => props.fluid)
const fluidGrowBoolean = useBooleanish(() => props.fluidGrow)
const startBoolean = useBooleanish(() => props.start)
const endBoolean = useBooleanish(() => props.end)
const thumbnailBoolean = useBooleanish(() => props.thumbnail)

const heightNumber = useToNumber(toRef(() => props.height ?? NaN))
const widthNumber = useToNumber(toRef(() => props.width ?? NaN))

const computedSrcset = computed(() =>
    typeof props.srcset === 'string'
        ? props.srcset
            .split(',')
            .filter((x) => x)
            .join(',')
        : Array.isArray(props.srcset)
            ? props.srcset.filter((x) => x).join(',')
            : undefined
)

const computedSizes = computed(() =>
    typeof props.sizes === 'string'
        ? props.sizes
            .split(',')
            .filter((x) => x)
            .join(',')
        : Array.isArray(props.sizes)
            ? props.sizes.filter((x) => x).join(',')
            : undefined
)

const computedDimentions = computed<{height: number | undefined; width: number | undefined}>(() => {
  const width = Number.isNaN(widthNumber.value) ? undefined : widthNumber.value
  const height = Number.isNaN(heightNumber.value) ? undefined : heightNumber.value
  if (blankBoolean.value) {
    if (width !== undefined && height === undefined) {
      return {height: width, width}
    }
    if (width === undefined && height !== undefined) {
      return {height, width: height}
    }
    if (width === undefined && height === undefined) {
      return {height: 1, width: 1}
    }
  }
  return {
    width,
    height,
  }
})

const computedBlankImgSrc = computed(() =>
    makeBlankImgSrc(computedDimentions.value.width, computedDimentions.value.height, props.blankColor)
)

const alignment = computed(() =>
    startBoolean.value
        ? 'float-start'
        : endBoolean.value
            ? 'float-end'
            : centerBoolean.value
                ? 'mx-auto'
                : undefined
)

const computedClasses = computed(() => [props.imgClass,
  {
  'p-1 bg-white border border-inherit rounded max-w-full': thumbnailBoolean.value,
  'max-w-full h-full': fluidBoolean.value || fluidGrowBoolean.value,
  'max-w-full': fluidGrowBoolean.value,
  'rounded': props.rounded === '' || props.rounded === true,
  [`rounded-${props.rounded}`]: typeof props.rounded === 'string' && props.rounded !== '',
  [`${alignment.value}`]: alignment.value !== undefined,
  'block': blockBoolean.value || centerBoolean.value,
}])

const makeBlankImgSrc = (
    width: number | undefined,
    height: number | undefined,
    color: string
): string => {
  const src = encodeURIComponent(
      BLANK_TEMPLATE.replace('%{w}', String(width))
          .replace('%{h}', String(height))
          .replace('%{f}', color)
  )
  return `data:image/svg+xml;charset=UTF-8,${src}`
}
</script>

<template>
  <img
      alt=""
      :class="computedClasses"
      :height="computedDimentions.height || undefined"
      :loading="lazyBoolean ? 'lazy' : 'eager'"
      :sizes="!blankBoolean ? computedSizes : undefined"
      :src="!blankBoolean ? props.src : computedBlankImgSrc"
      :srcset="!blankBoolean ? computedSrcset : undefined"
      :width="computedDimentions.width || undefined"
  />
</template>

<style scoped>

</style>