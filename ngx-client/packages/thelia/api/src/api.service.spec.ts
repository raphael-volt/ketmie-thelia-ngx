import { TestBed, inject, async } from '@angular/core/testing';
import { HttpModule, Http, XHRBackend, BaseRequestOptions } from "@angular/http";
import { ApiModule } from "./api.module";
import { ApiService, replaceCategoryIdInURL, replaceProductIdInURL } from './api.service';
import { RequestService } from "./request.service";
import { SessionService } from "./session.service";
import { LocalStorageModule, LocalStorageService } from "angular-2-local-storage";

let api: ApiService
describe('ApiService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        RequestService,
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
            provide: SessionService,
            deps: [LocalStorageService],
            useFactory: (storage: LocalStorageService)=> {
              return new SessionService(storage)
            }
        },
        {
          provide: ApiService,
          deps: [Http, RequestService, SessionService],
          useFactory: (h: Http, request: RequestService, session: SessionService) => {
            if (!api)
              api = new ApiService(h, session, request)
            return api
          }
        }
      ],
      imports: [
        HttpModule, 
        ApiModule
      ]
    });
  });

  it('should be created', inject([ApiService], (service: ApiService) => {
    expect(service).toBeTruthy();
    expect(api).toBeTruthy();

    let ru = "category/1/product/4#foo"
    ru = replaceProductIdInURL(ru, "10")
    expect(ru).toEqual('category/1/product/10#foo')
    ru = replaceCategoryIdInURL(ru, "5")
    expect(ru).toEqual('category/5/product/10#foo')
    
    ru = '/category/3/product/33'
    ru = replaceProductIdInURL(ru, "10")
    expect(ru).toEqual('/category/3/product/10')
    console.log(ru)

  }))

});
