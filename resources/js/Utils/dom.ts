import type {Slot} from "vue";

export const isEmptySlot = (el: Slot | undefined): boolean => (el?.() ?? []).length === 0