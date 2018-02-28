import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LocalStorageModule, ILocalStorageServiceConfig } from "angular-2-local-storage";
import { HttpModule } from "@angular/http";
import { ReactiveFormsModule, FormsModule } from "@angular/forms";

import { ApiService } from "./api.service";
import { RequestService } from "./request.service";
import { SessionService } from "./session.service";
import { ImgLoaderService } from "./img-loader.service";
import { CustomerService } from "./customer.service";
import { CardService } from "./card.service";
import { LayoutService } from "./layout.service";
import { DeclinationService } from "./declination.service";


const storageConfig: ILocalStorageServiceConfig = {
  prefix: "ketmie",
  storageType: "localStorage"
}

@NgModule({
  imports: [
    FormsModule,
    ReactiveFormsModule,
    CommonModule,
    HttpModule,
    LocalStorageModule.withConfig(storageConfig)
  ],
  exports: [
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [],
  providers: [
    ApiService,
    SessionService,
    RequestService,
    ImgLoaderService,
    CustomerService,
    CardService,
    DeclinationService,
    LayoutService
  ]
})
export class ApiModule { }
