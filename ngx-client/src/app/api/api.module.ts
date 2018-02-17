import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpModule } from "@angular/http";
import { ApiService } from "./api.service";
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
    ImgLoaderService
  ]
})
export class ApiModule { }
