import { Injectable, EventEmitter } from '@angular/core';
import { ApiService } from "./api.service";
import { RequestService } from "./request.service";
import { APIResponse, isAPIResponseError, Customer } from "./api.model";
import { Http, 
  Headers,
  RequestOptionsArgs, RequestMethod, Request, 
  Response, ResponseContentType 
} from "@angular/http";
import { Observable, Observer, Subscription } from "rxjs";
import { map, catchError } from "rxjs/operators";

@Injectable()
export class CustomerService {
  
  constructor(
    private api: ApiService,
    private request: RequestService
  ) { }
 
  
  customer: Customer
  private _logedin: boolean = false
  customerChange: EventEmitter<Customer> = new EventEmitter()
  loggedInChange: EventEmitter<boolean> = new EventEmitter()

  get logedIn(): boolean {
    return this._logedin
  }

  getCurrent(): Observable<Customer> {

    const params = this.request.getCustomerParams("current")
    const request: Request = this.request.getRequest(params)

    return this.api.get(request).pipe(
      map(response => {
        this.setCurrentCustomer(response.body)
        return this.customer
      }),
      catchError((err, caught) => {
        console.error(err)
        return caught
      })
    )

  }
  
  logout(): Observable<APIResponse> {

    const params = this.request.getCustomerParams("logout")
    
    const request: Request = this.request.getRequest( params)

    return this.api.get(request).pipe(
      map(response => {
        this.setCurrentCustomer(null)
        return response
      })
    )

  }
  login(user: Customer): Observable<Customer> {
    const params = this.request.getCustomerParams("login")
    const req = this.request.getRequest(params, user)

    return this.api.post(req).pipe(
      map(customer => {
        if(customer) {
          Object.assign(user, customer)
        }
        return this.setCurrentCustomer(user)
      })
    )
  }

  private setCurrentCustomer(value: Customer) {
    const logedIn = Boolean(value)
    if(value)
      value.loggedIn = logedIn
    if(logedIn != this._logedin) {
      this._logedin = logedIn
      this.loggedInChange.emit(this._logedin)
    }
    if(this.customer != value) {
      this.customer = value
      this.customerChange.emit(value)
    }
    return value
  }
}
