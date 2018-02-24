import { Directive, ElementRef, OnDestroy } from '@angular/core';
import { CustomerService } from "../customer.service";
import { Customer } from "../api.model";
import { Subscription, Subject } from "rxjs";
import "rxjs/add/operator/takeUntil";
@Directive({
  selector: '[customerEmail]',
  host: {
    'class': 'customer-email'
  }
})
export class CustomerEmailDirective implements OnDestroy {

  private customerSub: Subject<boolean> = new Subject()
  private host: HTMLElement
  constructor(service: CustomerService, ref:ElementRef) { 
    console.warn("[CustomerEmailDirective.constructor]");
    this.host = ref.nativeElement
    service.customerChange.takeUntil(this.customerSub).subscribe(this.customerChanged)
    this.customerChanged(service.customer)
  }

  private customerChanged = (customer?: Customer) => {
    console.log("customerChanged");
    
    this.host.innerHTML = (customer && (typeof customer.email == 'string')) ? customer.email:''
  }

  ngOnDestroy() {
    this.customerSub.next(false)
    this.customerSub.complete()
  }
}
