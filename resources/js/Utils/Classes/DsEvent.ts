/**
 * Items that are omitted from the DsEventInit constructor arg
 */
export type DsEventConstructorOmittables = 'eventType' | 'defaultPrevented'

export default class DsEvent {
    readonly cancelable: boolean = true
    readonly componentId: string | null = null
    private _defaultPrevented = false
    readonly eventType: string = ''
    readonly nativeEvent: string | null = null
    private _preventDefault: () => void
    readonly relatedTarget: EventTarget | null = null
    readonly target: EventTarget | null = null
    // Readable by everyone,
    // But only overwritten by inherited constructors
    public get defaultPrevented() {
        return this._defaultPrevented
    }
    protected set defaultPrevented(prop) {
        this._defaultPrevented = prop
    }
    // I think this is right
    // We want to be able to have it callable to everyone,
    // But only overwritten by inherited constructors
    public get preventDefault() {
        return this._preventDefault
    }
    // This may not be correct, because it doesn't get correct type inferences in children
    // Ex overwrite this.preventDefault = () => true is valid. Could be a TS issue
    protected set preventDefault(setter: () => void) {
        this._preventDefault = setter
    }

    constructor(
        eventType: string,
        eventInit: Partial<Omit<DsEvent, DsEventConstructorOmittables>> = {}
    ) {
        if (!eventType) {
            throw new TypeError(
                `Failed to construct '${this.constructor.name}'. 1 argument required, ${arguments.length} given.`
            )
        }

        // Merge defaults first, the eventInit, and the type last
        // so, it can't be overwritten
        Object.assign(this, DsEvent.Defaults, eventInit, {eventType})

        this._preventDefault = function _preventDefault() {
            if (this.cancelable) {
                this.defaultPrevented = true
            }
        }
    }

    static get Defaults() {
        return {
            cancelable: true,
            componentId: null,
            eventType: '',
            nativeEvent: null,
            relatedTarget: null,
            target: null,
        }
    }
}