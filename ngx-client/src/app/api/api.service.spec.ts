import { TestBed, inject, async } from '@angular/core/testing';
import { HttpModule, Http, XHRBackend, BaseRequestOptions } from "@angular/http";
import { ApiService, replaceCategoryIdInURL, replaceProductIdInURL } from './api.service';

let api: ApiService
describe('ApiService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
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
        }
      ],
      imports: [
        HttpModule
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
