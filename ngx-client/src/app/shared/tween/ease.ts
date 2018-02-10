import { EventEmitter } from "@angular/core";
type TweenEventType = "change" | "start" | "end"
export class TweenEvent {
    constructor(type: TweenEventType, tween: Tween) {
        this.type = type
        this.tween = tween
    }
    tween: Tween
    type: TweenEventType
    get currentValue(): number {
        return this.tween.currentValue
    }
}

const request = (callback: FrameRequestCallback): any => {
    return window.requestAnimationFrame(callback)
}
const cancel = (id) => {
    window.cancelAnimationFrame(id)
    return null
}

const now = () => {
    return Date.now()
}

export type PlayStatus = "none" | "toEnd" | "toStart"
export class Tween {

    private static _tweens: Tween[] = []
    private static _now: number = now()
    private static _requestID: number = NaN

    private static _addRequestAnimation(t: Tween) {
        const l = Tween._tweens
        const i = l.indexOf(t)
        if (i == -1) {
            Tween._tweens.push(t)
            if (isNaN(Tween._requestID))
                Tween._requestAnimation()
        }
    }

    private static _removeRequestAnimation(t: Tween) {
        const l = Tween._tweens
        const i = l.indexOf(t)
        if (i != -1) {
            l.splice(i, 1)
            if (!l.length && !isNaN(Tween._requestID)) {
                Tween._clearAnimation()
            }
        }
    }

    private static _clearAnimation() {
        window.cancelAnimationFrame(Tween._requestID)
        Tween._requestID = NaN
    }

    private static _requestAnimation() {
        Tween._requestID = window.requestAnimationFrame(Tween._tick)
    }

    private static _tick = () => {
        Tween._now = now()
        const l = Tween._tweens
        for (const t of l) {
            t.update()
        }
        if (l.length)
            Tween._requestAnimation()
    }
    constructor(public ease: Ease) { }

    private _currentValue: number = NaN
    change: EventEmitter<TweenEvent> = new EventEmitter<TweenEvent>()

    private _running: boolean
    get running(): boolean {
        return this._running
    }
    private _playStatus: PlayStatus
    get playStatus(): PlayStatus {
        return this._playStatus
    }
    private paused: boolean = false
    get currentValue(): number {
        return this._currentValue
    }
    private startT: number = 0
    private endT: number = 0
    private elapsedT: number = 0
    private t: number = 0
    private requestId: any | null

    protected update = () => {
        let e: TweenEvent
        const ease = this.ease
        const n = Tween._now
        const duration = ease.duration

        let elapsedT = n - this.startT
        if (elapsedT > duration)
            elapsedT = duration
        this.elapsedT = elapsedT
        let t: number = elapsedT / duration
        if (this._playStatus == "toStart") {
            t = 1 - t
        }
        this.t = t
        this._currentValue = this.ease.get(elapsedT)
        if (elapsedT == duration) {
            this._running = false
            Tween._removeRequestAnimation(this)
            this.change.emit(new TweenEvent("end", this))
        }
        else
            this.change.emit(new TweenEvent("change", this))
    }
    start() {
        this.t = 0
        this.elapsedT = 0
        this.startT = now()
        this.endT = this.startT + this.ease.duration

        this.paused = false
        this._running = true
        this._playStatus = "toEnd"
        this._currentValue = this.ease.start
        Tween._addRequestAnimation(this)
    }
    pause() {
        if (this.paused)
            return
        Tween._removeRequestAnimation(this)
        this.paused = true
        this._running = false
    }
    resume() {
        if (!this.paused)
            return
        let n = now()
        this.startT = n - this.elapsedT
        this.endT = this.startT + this.ease.duration

        this.paused = false
        this._running = true
        Tween._addRequestAnimation(this)
    }
    toogle() {
        let status: PlayStatus = "none"
        switch (this._playStatus) {
            case "toEnd":
                status = "toStart"
                break;

            case "toStart":
                status = "toEnd"
                break;

            default:

                break;
        }
        if (status == "none")
            return
        this._playStatus = status
    }
}

export class Ease {
    constructor(public start: number, public end: number, public duration: number, public ease: EaseFunction) {
        this.calculateChange()
    }
    private calculateChange() {
        this.c = this.end - this.start
    }
    set(start: number = NaN, end: number = NaN, duration: number = NaN, ease: EaseFunction = null) {
        if (!isNaN(start))
            this.start = start
        if (!isNaN(end))
            this.end = end
        if (!isNaN(duration))
            this.duration = duration
        if (ease)
            this.ease = ease
        this.calculateChange()
    }

    private c: number

    get(t: number): number {
        if (t < 0)
            t = 0
        if (t > this.duration)
            t = this.duration
        return this.ease(t, this.start, this.c, this.duration)
    }
}

type EaseFunction = (time: number, start: number, end: number, duration: number) => number

export class Linear {

    static in(t: number, b: number, c: number, d: number): number {
        return c * t / d + b;
    }

    static out(t: number, b: number, c: number, d: number): number {
        return Linear.in(t, b, c, d)
    }

    static inOut(t: number, b: number, c: number, d: number): number {
        return Linear.in(t, b, c, d)
    }
}

export class Sine {

    static in(t: number, b: number, c: number, d: number): number {
        return -c * Math.cos(t / d * (Math.PI / 2)) + c + b
    }

    static out(t: number, b: number, c: number, d: number): number {
        return c * Math.sin(t / d * (Math.PI / 2)) + b
    }

    static inOut(t: number, b: number, c: number, d: number): number {
        return -c / 2 * (Math.cos(Math.PI * t / d) - 1) + b
    }
}

export class Quintic {

    static in(t: number, b: number, c: number, d: number): number {
        return c * (t /= d) * t * t * t * t + b
    }

    static out(t: number, b: number, c: number, d: number): number {
        return c * ((t = t / d - 1) * t * t * t * t + 1) + b
    }

    static inOut(t: number, b: number, c: number, d: number): number {
        if ((t /= d / 2) < 1)
            return c / 2 * t * t * t * t * t + b

        return c / 2 * ((t -= 2) * t * t * t * t + 2) + b
    }
}

export class Quartic {

    static in(t: number, b: number, c: number, d: number): number {
        return c * (t /= d) * t * t * t + b
    }

    static out(t: number, b: number, c: number, d: number): number {
        return -c * ((t = t / d - 1) * t * t * t - 1) + b
    }

    static inOut(t: number, b: number, c: number, d: number): number {
        if ((t /= d / 2) < 1)
            return c / 2 * t * t * t * t + b;

        return -c / 2 * ((t -= 2) * t * t * t - 2) + b
    }
}

export class Quadratic {

    static in(t: number, b: number, c: number, d: number): number {
        return c * (t /= d) * t + b
    }

    static out(t: number, b: number, c: number, d: number): number {
        return -c * (t /= d) * (t - 2) + b
    }

    static inOut(t: number, b: number, c: number, d: number): number {
        if ((t /= d / 2) < 1)
            return c / 2 * t * t + b

        return -c / 2 * ((--t) * (t - 2) - 1) + b
    }
}

const interpolate = (t: number, from: number, to: number): number => {
    return from + (to - from) * t
}
export { EaseFunction, TweenEventType, interpolate }