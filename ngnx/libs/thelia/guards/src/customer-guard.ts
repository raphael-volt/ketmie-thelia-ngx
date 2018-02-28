import { Injectable } from '@angular/core';
import { CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { Observable, Observer, Subscription } from 'rxjs';
import { map } from 'rxjs/operators';
import { PopupService } from '@ngnx/thelia/common';
import { CustomerService } from '@ngnx/thelia/api';
import { ConnectionFormComponent } from '@ngnx/thelia/components';
@Injectable()
export class CustomerGuard implements CanActivate {
  constructor(private service: CustomerService, private dialog: PopupService) {}

  canActivate(
    route: ActivatedRouteSnapshot,
    state: RouterStateSnapshot
  ): Observable<boolean> | Promise<boolean> | boolean {
    const service = this.service;

    if (service.logedIn) return true;

    return Observable.create(o => {
      const addCustomerPopup = () => {
        let sub = this.dialog.afterClose(ConnectionFormComponent).subscribe((result: Observer<any>) => {
          sub.unsubscribe();
          o.next(service.logedIn);
          o.complete();
        });
      };
      if (service.currentBuzy) {
        let sub: Subscription = service.customerChange.subscribe(customer => {
          sub.unsubscribe();
          if (service.logedIn) {
            o.next(true);
            return o.complete();
          }
          addCustomerPopup();
        });
      } else addCustomerPopup();
    });
  }
}
