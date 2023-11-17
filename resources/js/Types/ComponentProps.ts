import type {Booleanish} from "@/js/Types/Booleanish";

export interface DImgProps {
    blank?: Booleanish
    blankColor?: string
    block?: Booleanish
    center?: Booleanish
    fluid?: Booleanish
    lazy?: Booleanish
    fluidGrow?: Booleanish
    height?: number | string
    start?: Booleanish
    end?: Booleanish
    rounded?: boolean | string
    sizes?: string | string[]
    src?: string
    srcset?: string | string[]
    thumbnail?: Booleanish
    width?: number | string
    imgClass?: string
}

export interface DFormProps {
    id?: string
    novalidate?: Booleanish
    inline?: Booleanish
    space?: number | string
}