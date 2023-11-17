import type {Booleanish} from "@/js/Types/Booleanish";

export const isBooleanish = (input: unknown): input is Booleanish =>
    typeof input === 'boolean' || input === '' || input === 'true' || input === 'false'

export const resolveBooleanish = (input: Booleanish): boolean =>
    typeof input === 'boolean' ? input : input === '' || input === 'true'