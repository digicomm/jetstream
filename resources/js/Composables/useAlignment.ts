import {computedEager} from "@vueuse/core";
import {type MaybeRefOrGetter, type Ref, toValue} from "vue";
import type {AlignmentJustifyContent} from "@/js/Types/AlignmentJustifyContent";

export default (
    align: MaybeRefOrGetter<AlignmentJustifyContent | undefined>
): Readonly<Ref<string>> =>
    computedEager(() => {
        const value = toValue(align)
        return !value ? '' : `justify-${value}`
    })