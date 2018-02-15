import { Injectable } from "@angular/core";
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from "@angular/router";
import { Observable } from "rxjs";
import { map } from "rxjs/operators";
import { ApiService } from "../api/api.service";
@Injectable()
export class CatalogGuard implements CanActivate {

    constructor(private api: ApiService) { }
    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
        console.log("CatalogGuard canActivate")
        return this.api.getShopTree().pipe(
            map(tree=>{
                console.log("CatalogGuard activate")
                return true
            })
        )
    }
}