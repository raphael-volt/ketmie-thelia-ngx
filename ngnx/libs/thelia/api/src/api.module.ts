import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LocalStorageModule, ILocalStorageServiceConfig } from 'angular-2-local-storage';
import { HttpModule } from '@angular/http';

import { ApiService } from './api.service';
import { RequestService } from './request.service';
import { SessionService } from './session.service';
import { CustomerService } from './customer.service';
import { CardService } from './card.service';
import { DeclinationService } from './declination.service';
import { ProductUrlPipe } from './product-url.pipe';

const storageConfig: ILocalStorageServiceConfig = {
  prefix: 'ketmie',
  storageType: 'localStorage'
};

@NgModule({
  imports: [CommonModule, HttpModule, LocalStorageModule.withConfig(storageConfig)],
  providers: [ApiService, SessionService, RequestService, CustomerService, CardService, DeclinationService],
  exports: [ProductUrlPipe],
  declarations: [ProductUrlPipe]
})
export class ApiModule {}
