import { Injectable } from "@angular/core";
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from "@angular/router";
import { Observable, Observer, Subscription } from "rxjs";
import { map } from "rxjs/operators";
import { CustomerService } from "../api/customer.service";
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material';
import { ConnectionFormComponent } from "../templates/customer/connection-form/connection-form.component"
import { defaultConfig } from "../mat/mat.module";
@Injectable()
export class CustomerGuard implements CanActivate {

    constructor(
        private service: CustomerService,
        private dialog: MatDialog) { }

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
        const service = this.service

        if (service.logedIn)
            return true

        return Observable.create(o => {
            const addCustomerPopup = () => {
                let ref: MatDialogRef<ConnectionFormComponent> = this.dialog.open(ConnectionFormComponent, defaultConfig)
    
                let sub = ref.afterClosed().subscribe((result: Observer<any>) => {
                    sub.unsubscribe()
                    o.next(service.logedIn)
                    o.complete()
                })

            }
            if(service.currentBuzy) {
                let sub: Subscription = service.customerChange.subscribe(customer=>{
                    sub.unsubscribe()
                    if(service.logedIn) {
                        o.next(true)
                        return o.complete()
                    }
                    addCustomerPopup()
                })
            }
            else
                addCustomerPopup()
        })
    }
}