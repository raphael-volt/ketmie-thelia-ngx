import { TestBed, inject, async } from '@angular/core/testing';
import { HttpModule, Http, XHRBackend, BaseRequestOptions } from "@angular/http";
import { ApiService } from './api.service';
import { Customer } from "./api.model";
import { CustomerService } from './customer.service';

let api: ApiService
let customer: CustomerService
const devUser: Customer = {
  email: "devcustomer@ketmie.com",
  motdepasse: "dev1234",
  loggedIn: false
}
describe('CustomerService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [
        HttpModule
      ],
      providers: [
        XHRBackend,
        BaseRequestOptions,
        {
          provide: Http,
          deps: [XHRBackend, BaseRequestOptions],
          useFactory: (xhr: XHRBackend, opt: BaseRequestOptions) => {
            return new Http(xhr, opt)
          }
        },
        {
          provide: ApiService,
          deps: [Http],
          useFactory: (h: Http) => {
            if (!api)
              api = new ApiService(h)
            return api
          }
        },
        {
          provide: CustomerService,
          deps: [ApiService],
          useFactory: (api: ApiService) => {
            if (!customer)
              customer = new CustomerService(api)
            return customer
          }
        }
      ]
    });
  });

  describe("service", ()=>{
    it('should be created', inject([CustomerService], (service: CustomerService) => {
      expect(service).toBeTruthy();
      expect(service).toEqual(customer)
    }));
  })
  describe("customer", ()=>{
    it('should be loggedin', async(()=>{
      let sub = customer.login(devUser).subscribe(user=>{
        expect(user.loggedIn).toBeTruthy()
        expect(customer.logedIn).toBeTruthy()
      })
    }))
  })
});
