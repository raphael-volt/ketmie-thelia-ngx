import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ApiModule } from '@ngnx/thelia/api';
import { TheliaCommonModule } from '@ngnx/thelia/common';
import { ComponentsModule } from '@ngnx/thelia/components';
import { CatalogGuard } from './catalog-guard';
import { CustomerGuard } from './customer-guard';
import { DeactivableGuard } from './deactivable-guard';

@NgModule({
  imports: [CommonModule, ApiModule, ComponentsModule],
  providers: [CatalogGuard, CustomerGuard, DeactivableGuard]
})
export class GuardsModule {}
