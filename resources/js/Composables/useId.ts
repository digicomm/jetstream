let id = 0
function generateId() {
    return ++id
}

export function useGeneratedId() {
    return generateId()
}



import {getId} from '@/js/Utils'
import {computed, type ComputedRef, type MaybeRefOrGetter, toValue} from 'vue'

export default (id?: MaybeRefOrGetter<string | undefined>, suffix?: string): ComputedRef<string> =>
    computed(() => toValue(id) || getId(suffix))

