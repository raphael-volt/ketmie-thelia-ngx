import { Component, Injectable, EventEmitter } from "@angular/core";
import {
    CanDeactivate,
    ActivatedRouteSnapshot,
    RouterStateSnapshot
} from "@angular/router";
import { Observable, Observer, Subscription } from "rxjs";
import { DeactivableComponent } from "./deactivable.component";

@Injectable()
export class DeactivableGuard implements CanDeactivate<DeactivableComponent> {

    private deactivables: DeactivableComponent[] = []
    private sub:Subscription
    private event = new EventEmitter<any>()
    canDeactivate(
        component: DeactivableComponent,
        currentRoute: ActivatedRouteSnapshot,
        currentState: RouterStateSnapshot,
        nextState: RouterStateSnapshot
    ): Observable<boolean> {
        let i = this.deactivables.indexOf(component)
        if(i<0)
            this.deactivables.push(component)
        this.check()
        return Observable.create((o:Observer<any>)=>{
            let s:Subscription = this.event.subscribe(v=>{
                if(v==component) {
                    if(s)
                        s.unsubscribe()
                    o.next(true)
                    o.complete()
                }
            })
        })
    }

    private check() {
        if(!this.sub) {
            const c = this.deactivables[0]
            this.sub = c.deactivate().subscribe(v=>{
                this.sub.unsubscribe()
                this.sub = null
                this.event.emit(this.deactivables.shift())
                if(this.deactivables.length)
                    this.check()
            })
        }
    }
}