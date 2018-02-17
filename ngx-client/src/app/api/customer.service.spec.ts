import { TestBed, inject, async } from '@angular/core/testing';
import { HttpModule, Http, XHRBackend, BaseRequestOptions, RequestMethod } from "@angular/http";
import { LocalStorageModule, LocalStorageService } from "angular-2-local-storage";
import { ApiModule } from "./api.module";
import { ApiService } from './api.service';
import { Customer } from "./api.model";
import { CustomerService } from './customer.service';

let api: ApiService
let customer: CustomerService
type Session = {
  session_id: string
  action: string
}
let session: Session
let devUser: Customer = {
  email: "devcustomer@ketmie.com",
  motdepasse: "dev1234",
  loggedIn: false
}
describe('CustomerService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [
        HttpModule,
        ApiModule
      ],
      providers: [
        LocalStorageService,
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
          deps: [Http, LocalStorageService],
          useFactory: (h: Http, storage: LocalStorageService) => {
            if (!api)
              api = new ApiService(h, storage)
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

  describe("service", () => {
    it('should be created', inject([CustomerService], (service: CustomerService) => {
      expect(service).toBeTruthy();
      expect(service).toEqual(customer)
    }));
    it("should register session", async(() => {
      if (!api.http.hasSession) {
        api.http.registerSession().subscribe(success => {
          expect(success).toBeTruthy()
        })
      }
      else {
        setTimeout(() => {
          expect(true).toBeTruthy()
        }, 100);
      }
    }))
    
    it('should login', async(() => {
      const http = api.http
      let sub = http.login(devUser)
        .subscribe(
          customer => {
            sub.unsubscribe()
            expect(customer).toBeTruthy()
            expect(customer).toEqual(devUser)
            expect(devUser.loggedIn).toBeTruthy()
          },
          err => {
            throw err
          })
    }))
    
    it('should have a session', async(() => {
      const http = api.http
      let sub = http.currentCustomer().subscribe(
          customer => {
            sub.unsubscribe()
            expect(customer).toBeTruthy()
            expect(customer.id).toEqual(devUser.id)
          },
          err => {
            throw err
          })
    }))
    
    it('should logout', async(() => {
      const http = api.http
      let sub = http.logout().subscribe(
          response => {
            sub.unsubscribe()
            expect(response.success).toBeTruthy()
          },
          err => {
            throw err
          })
    }))
    
    it('current customer should be null', async(() => {
      const http = api.http
      let sub = http.currentCustomer().subscribe(
          customer => {
            sub.unsubscribe()
            expect(customer).toBeFalsy()
            expect(customer).toBeNull()
          },
          err => {
            throw err
          })
    }))
  })
})
