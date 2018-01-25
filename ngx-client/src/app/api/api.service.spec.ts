import { TestBed, inject, async } from '@angular/core/testing';
import { HttpModule, Http, XHRBackend, BaseRequestOptions } from "@angular/http";
import { ApiService } from './api.service';

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
            return new ApiService(h)
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
  }));

  it('should get catalog', async(inject([ApiService], (service: ApiService) => {
    service.getShopCategories().subscribe(catatlog => {
      expect(catatlog.id).toEqual(0)
    })
  })));

});
