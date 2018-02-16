import { Injectable, EventEmitter } from '@angular/core';
import { ApiService } from "./api.service";
import { APIResponse, isAPIResponseError, Customer } from "./api.model";
import { Http, Response, RequestOptionsArgs, RequestMethod, Request, ResponseContentType } from "@angular/http";
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

  private getRequest(method: RequestMode, search:URLSearchParams=null, body: any=null) {
    return new Request({
      method: RequestMethod.Post,
      url: this.api.baseHref,
      responseType: ResponseContentType.Json,
      body: body,
      search: search
    })
  }
  login(user: Customer): Observable<Customer> {

    const params = this.api.getSearchParam("customer", { action: "login" })
    const request: Request = this.api.getRequest(RequestMethod.Post, params, user)

    return this.http.request(request).pipe(
      map(response => {
        const res = this.api.getApiResponse(response)
        if(res.success) {
          Object.assign(user, res.body)
        }
        return this.setCurrentCustomer(user)
      }),
      catchError((err, caught) => {
        console.error(err)
        return caught
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
  
  getCurrent(): Observable<Customer> {

    const params = this.api.getSearchParam("customer", { action: "current" })
    const request: Request = this.api.getRequest(RequestMethod.Post, params)

    return this.http.request(request).pipe(
      map(response => {
        const res = this.api.getApiResponse(response)
        this.setCurrentCustomer(res.body)
        return this.customer
      }),
      catchError((err, caught) => {
        console.error(err)
        return caught
      })
    )

  }
  
  private get http(): Http {
    return this.api.http
  }
}
