import { Injectable, EventEmitter } from "@angular/core";
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from "@angular/router";
import { Observable, Observer, Subscription } from "rxjs";
import { map } from "rxjs/operators";
import { ApiService } from "../api/api.service";
import { RequestService } from "../api/request.service";
import { SessionService } from "../api/session.service";
import { APISession } from "../api/api.model";
import { CardService } from "../api/card.service";
import { one } from "../shared/utils/subscription.utils";
@Injectable()
export class CatalogGuard implements CanActivate {

    private activated: boolean
    private activable: EventEmitter<boolean> = new EventEmitter<boolean>()
    constructor(
        private api: ApiService,
        private session: SessionService,
        private card: CardService,
        private request: RequestService) {

        this.init(api, card)
    }

    private init(
        api: ApiService,
        card: CardService) {
        const done = (next?) => {
            this.activated = true
            this.activable.emit(true)
        }
        let checkApi = () => {
            if (api.initialized)
                return checkCard()
            one(api.initializedChange.asObservable(), checkCard)
        }

        let checkCard = () => {
            if (card.hasCard) {
                return done()
            }
            card.get(done)
        }

        checkApi()
    }

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
        if (this.activated)
            return true
        return this.activable.asObservable()
    }
}