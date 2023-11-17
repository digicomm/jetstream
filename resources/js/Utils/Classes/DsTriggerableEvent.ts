import DsEvent, {type DsEventConstructorOmittables} from './DsEvent'

export default class DsTriggerableEvent extends DsEvent {
    readonly trigger: string | null = null

    constructor(
        eventType: string,
        eventInit: Partial<Omit<DsTriggerableEvent, DsEventConstructorOmittables>> = {}
    ) {
        super(eventType, eventInit)

        // Merge defaults first, the eventInit, and the type last
        // so, it can't be overwritten
        Object.assign(this, DsEvent.Defaults, eventInit, {eventType})
    }

    static get Defaults() {
        return {
            ...super.Defaults,
            trigger: null,
        }
    }
}