import { Injectable, EventEmitter } from '@angular/core';
import { ApiService, HttpService } from "./api.service";
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
    private api: ApiService
  ) { }
 
  
  customer: Customer
  private _logedin: boolean = false
  customerChange: EventEmitter<Customer> = new EventEmitter()
  loggedInChange: EventEmitter<boolean> = new EventEmitter()

  get logedIn(): boolean {
    return this._logedin
  }

  getCurrent(): Observable<Customer> {

    const params = this.http.getSearchParam("customer", { action: "current" })
    const request: Request = this.http.getRequest(params)

    return this.http.get(request).pipe(
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

    const params = this.http.getSearchParam("customer", { 
      action: "logout"
    })
    
    const request: Request = this.http.getRequest( params, {
      action:"deconnexion"
    })

    return this.http.get(request).pipe(
      map(response => {
        this.setCurrentCustomer(null)
        return response
      }),
      catchError((err, caught) => {
        console.error(err)
        return caught
      })
    )

  }
  login(user: Customer): Observable<Customer> {
    
    return this.http.login(user).pipe(
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
   
  private get http(): HttpService {
    return this.api.http
  }
}
