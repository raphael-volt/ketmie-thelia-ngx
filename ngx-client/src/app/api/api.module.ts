import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LocalStorageModule, ILocalStorageServiceConfig } from "angular-2-local-storage";
import { HttpModule } from "@angular/http";
import { ReactiveFormsModule, FormsModule } from "@angular/forms";
import { MatModule } from "../mat/mat.module";

import { ApiService } from "./api.service";
import { RequestService } from "./request.service";
import { SessionService } from "./session.service";
import { ImgLoaderService } from "./img-loader.service";
import { CustomerService } from "./customer.service";
import { CardService } from "./card.service";
import { CustomerEmailDirective } from './core/customer-email.directive';
import { DeclinationPipe } from './core/declination.pipe';
import { DeclinationService } from "./declination.service";
import { 
  DeclinationController,
  DeclinationRadioGroupComponent, 
  DeclinationSelectComponent 
} from "./core/declination-controller";

const storageConfig: ILocalStorageServiceConfig = {
  prefix: "ketmie",
  storageType: "localStorage"
}

@NgModule({
  imports: [
    MatModule,
    FormsModule, 
    ReactiveFormsModule,
    CommonModule,
    HttpModule,
    LocalStorageModule.withConfig(storageConfig)
  ],
  exports:[
    CustomerEmailDirective,
    DeclinationPipe,
    DeclinationController,
    DeclinationSelectComponent,
    DeclinationRadioGroupComponent
  ],
  declarations: [
    CustomerEmailDirective, 
    DeclinationController,
    DeclinationSelectComponent, 
    DeclinationRadioGroupComponent,
    DeclinationPipe, 
  ],
  providers: [
    ApiService,
    SessionService,
    RequestService,
    ImgLoaderService,
    CustomerService,
    CardService,
    DeclinationService
  ]
})
export class ApiModule { }
