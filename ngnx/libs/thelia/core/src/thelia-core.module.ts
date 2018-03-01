import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { ApiModule } from '@ngnx/thelia/api';
import { MatEnvModule } from '@ngnx/mat-env';
import { TheliaCommonModule } from '@ngnx/thelia/common';
import { ComponentsModule } from '@ngnx/thelia/components';
import { RoutesModule } from '@ngnx/thelia/routes';
import { GuardsModule } from '@ngnx/thelia/guards';
import { SharedModule } from '@ngnx/thelia/shared';
@NgModule({
  imports: [
    SharedModule,
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    BrowserAnimationsModule,
    MatEnvModule,
    TheliaCommonModule,
    ApiModule,
    ComponentsModule,
    RoutesModule,
    GuardsModule
  ],
  exports: [
    SharedModule,
    FormsModule,
    ReactiveFormsModule,
    BrowserAnimationsModule,
    ApiModule,
    MatEnvModule,
    TheliaCommonModule,
    ComponentsModule,
    RoutesModule,
    GuardsModule
  ]
})
export class TheliaCoreModule {}
