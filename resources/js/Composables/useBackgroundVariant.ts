import {computed, type MaybeRefOrGetter, toValue} from "vue";
import type {ColorShade, ColorVariant} from "@/js/Types";

export default (
    obj: MaybeRefOrGetter<{
        textVariant: ColorVariant
        textVariantShade: ColorShade
        bgVariant: ColorVariant
        bgVariantShade: ColorShade
        borderVariant: ColorVariant
        borderVariantShade: ColorShade

    }>
) =>
    computed(() => {
        const props = toValue(obj)
        return {
            [`text-${props.textVariant}-${props.textVariantShade}`]: props.textVariant !== null && (props.textVariant !== 'black' && props.textVariant !== 'white' && props.textVariant !== 'transparent'),
            [`text-${props.textVariant}`]: props.textVariant !== null && (props.textVariant === 'black' || props.textVariant === 'white' || props.textVariant === 'transparent'),
            [`bg-${props.bgVariant}-${props.bgVariantShade}`]: props.bgVariant !== null && (props.bgVariant !== 'black' && props.bgVariant !== 'white' && props.bgVariant !== 'transparent'),
            [`bg-${props.textVariant}`]: props.bgVariant !== null && (props.bgVariant === 'black' || props.bgVariant === 'white' || props.bgVariant === 'transparent'),

        }
    })
