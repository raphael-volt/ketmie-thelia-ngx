import { Subscription } from "rxjs";
export class SubscriptionCollector {

    private _subs: Subscription[] = []


    set add(subsciption: Subscription) {
        this._subs.push(subsciption)
    }

    unsubscribeAll = (...args: any[])=> {
        if(! this._subs.length)
            return
        for(let s of this._subs)
            s.unsubscribe()
        this._subs.length = 0
    }
}