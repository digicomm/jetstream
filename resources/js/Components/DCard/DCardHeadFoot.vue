<script lang="ts" setup>
import {computed, toRef, useSlots} from "vue";
import {isEmptySlot} from "@/js/Utils/dom";

const props = withDefaults(
    defineProps<
        {
          tag?: string
          footer?: boolean
          title?: string
          subtitle?: string
          actions?: boolean
          actionsId?: string
          tableActions?: boolean
          tableActionsId?: string
          bgClass?: string
          defaultClass?: string
          containerClass?: string
          additionalContainerClass?: string
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
      footer: false,
      bgClass: 'bg-gray-50 dark:bg-gray-700/75',
      defaultClass: 'text-center space-y-3',
      containerClass: 'sm:space-y-0 sm:text-left sm:flex sm:justify-between sm:items-center',
      additionalContainerClass: '',
      p: '5',
      pX: null,
      pY: null,
      pT: null,
      pB: null,
      pL: null,
      pR: null,
      actions: false,
      actionsId: 'actions',
      tableActions: false,
      tableActionsId: 'tableActions',
      title: null,
      subtitle: null,
    }
)

const slots = useSlots()

const computedClasses = computed(() => [
  {
    [`p-${props.p}`]: props.p !== null && (props.pX === null || props.pY === null),
    [`px-${props.pX}`]: props.pX !== null,
    [`py-${props.pY}`]: props.pY !== null,
    [`pt-${props.pT}`]: props.pT !== null,
    [`pb-${props.pB}`]: props.pB !== null,
    [`pl-${props.pL}`]: props.pL !== null,
    [`pr-${props.pR}`]: props.pR !== null,
  },
  [props.bgClass],
  [props.defaultClass],
  [props.containerClass],
    [props.additionalContainerClass]
])

defineSlots<{
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  default?: (props: Record<string, never>) => any
  actions?: (props: Record<string, never>) => any
  tableActions?: (props: Record<string, never>) => any
}>()

const hasActionsSlot = toRef(() => !isEmptySlot(slots.actions))
</script>

<template>
  <!-- Card Header/Footer -->
  <component :is="tag" :class="computedClasses">

    <slot v-if="!footer">
      <div>
        <h3 v-if="!subtitle" class="font-semibold">
          {{ title }}
        </h3>
        <h3 v-else class="font-semibold mb-1">
          {{ title }}
        </h3>
        <h4 v-if="subtitle" class="text-sm font-medium text-gray-500 dark:text-gray-400">
          {{ subtitle }}
        </h4>
      </div>
      <div v-if="actions || hasActionsSlot" :id="actionsId">
        <slot name="actions">

        </slot>
      </div>
    </slot>
    <slot v-else>
    </slot>
    <div v-if="tableActions" :id="tableActionsId">
    </div>


  </component>
  <!-- END Card Header/Footer -->
</template>