import {computed, type MaybeRefOrGetter} from 'vue'

export default (value: MaybeRefOrGetter<string | never>) =>
    computed(() => {
        switch (value) {
            case 'xs':
                return 'px-2 py-1 text-xs leading-snug'
            case 'sm':
                return 'px-3 py-2 text-sm leading-normal'
            case 'md':
                return 'px-3 py-2 leading-normal'
            case 'lg':
                return 'px-5 py-3 text-lg leading-relaxed'
        }
    })