import { Injectable, EventEmitter } from '@angular/core';
import { ApiService } from './api.service';
import { RequestService } from './request.service';
import { APIResponse, isAPIResponseError, Customer, Address, Country } from '@ngnx/thelia/model';
import { Observable, Observer, Subscription } from 'rxjs';
import { map, catchError } from 'rxjs/operators';

@Injectable()
export class CustomerService {
  constructor(private api: ApiService, private request: RequestService) {
    let sub = this.getCountries().subscribe(v => {
      sub.unsubscribe();
    });
    let sub2 = this.getCurrent().subscribe(customer => {
      sub2.unsubscribe();
    });
  }

  customer: Customer;
  private _logedin: boolean = false;
  customerChange: EventEmitter<Customer> = new EventEmitter();
  loggedInChange: EventEmitter<boolean> = new EventEmitter();

  get logedIn(): boolean {
    return this._logedin;
  }

  private _currentBuzy: boolean;

  get currentBuzy(): boolean {
    return this._currentBuzy;
  }

  private countries: Country[];
  getCountries(): Observable<Country[]> {
    if (this.countries) return Observable.of(this.countries);
    return this.api.get(this.request.getRequest(this.request.getSearchParam('countries'))).pipe(
      map(response => {
        this.countries = response.body;
        return this.countries;
      })
    );
  }

  getCurrent(): Observable<Customer> {
    this._currentBuzy = true;
    const api = this.api;
    const request = this.request;
    let emitter: EventEmitter<boolean>;
    let obs: Observer<Customer>;
    let customer: Customer;
    const initialized: boolean = api.initialized;
    if (!initialized) {
      emitter = api.initializedChange;
    } else {
      emitter = new EventEmitter();
    }
    const done = () => {
      this._currentBuzy = false;
      this.setCurrentCustomer(customer);
      obs.next(customer);
      obs.complete();
    };
    let sub: Subscription = emitter.subscribe(value => {
      const params = request.getCustomerParams('current');
      const req = request.getRequest(params);
      if (sub) sub.unsubscribe();
      sub = this.api.get(req).subscribe(response => {
        sub.unsubscribe();
        customer = response.body;
        done();
      });
    });
    return Observable.create((observer: Observer<Customer>) => {
      obs = observer;
      if (initialized) {
        emitter.next(true);
      }
    });
  }

  logout(): Observable<APIResponse> {
    const params = this.request.getCustomerParams('logout');

    const request = this.request.getRequest(params);

    return this.api.get(request).pipe(
      map(response => {
        this.setCurrentCustomer(null);
        return response;
      })
    );
  }

  login(user: Customer): Observable<Customer> {
    const params = this.request.getCustomerParams('login');
    const req = this.request.getRequest(params, user);

    return this.api.post(req).pipe(
      map(response => {
        if (response.success) {
          Object.assign(user, response.body);
        } else user = null;
        return this.setCurrentCustomer(user);
      })
    );
  }

  getEmailTaken(email: string): Observable<boolean> {
    return this.api
      .get(this.request.getRequest(this.request.getSearchParam('customer', { method: 'emailTaken', email: email })))
      .pipe(map(response => response.body.taken));
  }

  createCustomer(customer: Customer): Observable<Customer> {
    return this.api.post(this.request.getRequest(this.request.getCustomerParams('create'), customer)).pipe(
      map(response => {
        if (response.success) {
          delete customer.motdepasse;
          Object.assign(customer, response.body);
          customer.isNew = true;
          this.setCurrentCustomer(customer);
          return customer;
        } else {
          console.error(response.body);
        }
        return response.body;
      })
    );
  }

  changeEmail(email: string): Observable<boolean> {
    return this.api.post(this.request.getRequest(this.request.getCustomerParams('update'), { email: email })).pipe(
      map(response => {
        if (response.success) {
          this.customer.email = email;
          this.customerChange.emit(this.customer);
        }
        return response.success;
      })
    );
  }

  updateCustomer(customer: Customer): Observable<boolean> {
    return this.api
      .post(this.request.getRequest(this.request.getCustomerParams('update'), { customer: customer }))
      .pipe(
        map(response => {
          if (response.success) {
            Object.assign(this.customer, customer);
            this.customerChange.emit(this.customer);
          }
          return response.success;
        })
      );
  }

  changePassword(password: string): Observable<boolean> {
    return this.api
      .post(this.request.getRequest(this.request.getCustomerParams('update'), { password: password }))
      .pipe(map(response => response.success));
  }

  getAdresses(): Observable<Address[]> {
    return this.api
      .get(this.request.getRequest(this.request.getCustomerParams('address')))
      .pipe(map(response => response.body));
  }

  createAddress(address: Address): Observable<Address> {
    return this.api
      .post(
        this.request.getRequest(this.request.getCustomerParams('address'), {
          method: 'create',
          address: address
        })
      )
      .pipe(
        map(response => {
          Object.assign(address, response.body);
          return address;
        })
      );
  }

  updateAddress(address: Address): Observable<boolean> {
    return this.api
      .post(
        this.request.getRequest(this.request.getCustomerParams('address'), {
          method: 'update',
          address: address
        })
      )
      .pipe(map(response => response.success));
  }

  deleteAddress(address: Address): Observable<boolean> {
    return this.api
      .post(
        this.request.getRequest(this.request.getCustomerParams('address'), {
          method: 'delete',
          address: { id: address.id }
        })
      )
      .pipe(map(response => response.success));
  }

  private setCurrentCustomer(value: Customer): Customer | null {
    const logedIn = Boolean(value);
    if (value) value.loggedIn = logedIn;
    this._logedin = logedIn;
    this.loggedInChange.emit(this._logedin);
    this.customer = value;
    this.customerChange.emit(value);
    return value;
  }
}
