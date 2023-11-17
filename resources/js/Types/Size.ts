export interface BaseSize {
    xs: unknown
    sm: unknown
    md: unknown
    lg: unknown
    xl: unknown
    '2xl': unknown
    '3xl': unknown
    '4xl': unknown
    '5xl': unknown
    '6xl': unknown
    '7xl': unknown
}

export type Size = keyof BaseSize