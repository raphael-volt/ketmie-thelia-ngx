import { Subject } from "rxjs";
import { EaseFunction, Sine } from "./ease";

const getNow = (): number => Date.now()

const _ease = (t: number, d: TweenData) => d.ease(
    t, d.start,
    d.end - d.start,
    d.duration)

const _tweens: TweenBase[] = []
let _now: number = getNow()
let _requestID: number = NaN
let _requestFlag: boolean = false

const _addRequestAnimation = (t: TweenBase) => {
    const i = _tweens.indexOf(t);
    if (i == -1) {
        _tweens.push(t);
        _requestAnimation();
    }
}

const _removeRequestAnimation = (t: TweenBase) => {
    const i = _tweens.indexOf(t);
    if (i != -1) {
        _tweens.splice(i, 1);
        if (_tweens.length == 0) {
            _clearAnimation();
        }
    }
}

const _clearAnimation = () => {
    window.cancelAnimationFrame(_requestID);
    _requestFlag = false
    _requestID = NaN;
}

const _requestAnimation = () => {
    if (_requestFlag || !_tweens.length) return;
    _requestFlag = true;
    _requestID = window.requestAnimationFrame(_tick);

}

const _tick = () => {
    _requestFlag = false;
    _now = getNow()
    for (const t of _tweens)
        t.update(_now);

    if (_tweens.length) _requestAnimation();
};

export interface TweenData {
    start: number
    end: number
    duration: number
    ease: EaseFunction
}

export interface TweenChangeEvent {
    target: TweenBase
    type?: "start" | "finish" | "change"
    value?: number
}

export type TweenStatus = "begin" | "end" | "in" | "out" | "pause"

export class TweenBase {

    statusChange: Subject<TweenStatus> = new Subject()
    events: Subject<TweenChangeEvent> = new Subject()

    private _time: number = 0
    private _reverseElapsed: number
    private _reverseTime: number
    private _startTime
    private _pauseFlag
    private _running: boolean = false
    private _reversed: boolean = false
    private _currentValue: number = 0
    private _elapsed: number = 0
    private _status: TweenStatus = "begin"

    get status(): TweenStatus {
        return this._status
    }

    private setStatus(value: TweenStatus) {
        if (this._status == value)
            return
        this._status = value
        this.statusChange.next(value)
    }

    get running() {
        return this._running
    }

    get reversed() {
        return this._reversed
    }

    get elapsed(): number {
        return this._elapsed
    }

    get currentValue(): number {
        return this._currentValue
    }

    constructor(public data?: TweenData) { }

    updateReversed = (now: number): boolean => {
        let et = now - this._reverseTime
        let t = this._reverseElapsed - et
        if (t < 0)
            t = 0
        this._time = t
        return t == 0
    }

    update = (t: number) => {
        const e: TweenChangeEvent = { target: this, type: "change" }
        this._elapsed = t - this._startTime
        let finished: boolean = false
        let started: boolean = false
        const d = this.data.duration
        if (this._reversed) {
            finished = this.updateReversed(t)
            started = this._time == d
        }
        else {
            let et = this._elapsed
            if (et > d)
                et = d
            this._time = et
            finished = et == d
            started = et == 0
        }
        if (finished) {
            this._reversed = this._time == d
            e.type = "finish"
        }
        else
            if (started)
                e.type = "start"
        e.value = this._currentValue = _ease(this._time, this.data)
        if (finished) {
            this._running = false
            this.setStatus(this._time == 0 ? "begin" : "end")
            _removeRequestAnimation(this)
        }
        this.events.next(e)
    }

    start() {
        this.setRunning(true, 'in', false, getNow())
        this._elapsed = 0
        this._time = 0
        this._reversed = false
        this.updateNow()
        _addRequestAnimation(this)
    }

    reverse() {
        this._reversed = true
        this._elapsed = this.data.duration
        this._reverseTime = getNow()
        this._reverseElapsed = this._elapsed
        this.setRunning(true, 'out', false, getNow())
        this.updateNow()
        _addRequestAnimation(this)
    }

    toggle() {
        let pauseFlag = this._pauseFlag
        if (!this._running) {
            if (!pauseFlag) {
                if (this._time == 0)
                    this.start()
                else
                    this.reverse()
                return
            }
        }
        this._reversed = !this._reversed
        this.setRunning(true, this._reversed ? "out" : "in", false)
        if (pauseFlag)
            _addRequestAnimation(this)
        const n = getNow()
        this._reverseTime = n
        this._reverseElapsed = this._time
        if (!this._reversed) {
            this._startTime = n - this._time
        }
        this.updateNow()
    }

    pause() {
        if (!this._pauseFlag) {
            this.setRunning(false, "pause", true)
            _removeRequestAnimation(this)
        }
    }

    resume() {
        if (this._pauseFlag) {
            this.setRunning(true, this._reversed ? "out" : "in", false, getNow() - this._elapsed)
            this.updateNow()
            _addRequestAnimation(this)
        }
    }

    setRunning(running: boolean, status: TweenStatus, pauseFlag: boolean = false, startTime: number = NaN) {
        this._running = running
        this._pauseFlag = pauseFlag
        this.setStatus(status)
        if (!isNaN(startTime))
            this._startTime = startTime
    }

    private updateNow() {
        this.update(getNow())
    }
}
