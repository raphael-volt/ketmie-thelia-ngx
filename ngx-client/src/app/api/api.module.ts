import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpModule } from "@angular/http";
import { ApiService } from "./api.service";
import { ImgLoaderService } from "./img-loader.service";
@NgModule({
  imports: [
    CommonModule,
    HttpModule
  ],
  declarations: [],
  providers: [
    ApiService,
    ImgLoaderService
  ]
})
export class ApiModule { }
