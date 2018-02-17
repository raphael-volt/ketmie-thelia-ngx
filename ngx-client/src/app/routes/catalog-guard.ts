import { Injectable } from "@angular/core";
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from "@angular/router";
import { Observable, Observer, Subscription } from "rxjs";
import { map } from "rxjs/operators";
import { ApiService } from "../api/api.service";
import { RequestService } from "../api/request.service";
import { SessionService } from "../api/session.service";
import { APISession } from "../api/api.model";
@Injectable()
export class CatalogGuard implements CanActivate {

    private treeFlag: boolean
    constructor(
        private api: ApiService,
        private session: SessionService,
        private request: RequestService) { }
    
    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
        if(this.api.initialized)
            return true
        return this.api.initialize().pipe(
            map(tree=>true)
        )
    }
}