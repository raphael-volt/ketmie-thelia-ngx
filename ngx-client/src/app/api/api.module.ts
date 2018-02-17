import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpModule } from "@angular/http";
import { ApiService } from "./api.service";
import { RequestService } from "./request.service";
import { SessionService } from "./session.service";
import { ImgLoaderService } from "./img-loader.service";
import { LocalStorageModule, ILocalStorageServiceConfig } from "angular-2-local-storage";
const storageConfig: ILocalStorageServiceConfig = {
  prefix: "ketmie",
  storageType: "localStorage"
}
@NgModule({
  imports: [
    CommonModule,
    HttpModule,
    LocalStorageModule.withConfig(storageConfig)
  ],
  declarations: [],
  providers: [
    ApiService,
    SessionService,
    RequestService,
    ImgLoaderService
  ]
})
export class ApiModule { }
