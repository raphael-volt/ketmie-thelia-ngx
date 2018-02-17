import { TestBed, inject, async } from '@angular/core/testing';
import { HttpModule, XHRBackend, BaseRequestOptions, Http } from "@angular/http";
import { ApiService } from "./api.service";
import { ImgLoaderService } from './img-loader.service';
import { LocalStorageModule, LocalStorageService } from "angular-2-local-storage";
describe('ImgLoaderService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
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
          useFactory: (h: Http, s: LocalStorageService) => {
            return new ApiService(h, s)
          }
        },
        {
          provide: ImgLoaderService,
          deps: [ApiService],
          useFactory: (api: ApiService) => {
            return new ImgLoaderService(api)
          }
        }
      ],
      imports: [
        HttpModule
      ]

    });
  });

  it('should be created', inject([ImgLoaderService], (service: ImgLoaderService) => {
    expect(service).toBeTruthy();
  }));
});
