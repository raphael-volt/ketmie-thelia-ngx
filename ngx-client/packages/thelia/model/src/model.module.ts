import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ModelComponent } from './model/model.component';
import { ModelService } from "./model.service";
@NgModule({
  imports: [
    CommonModule
  ],
  declarations: [ModelComponent],
  exports: [ModelComponent],
  providers: [ModelService]
})
export class ModelModule { }

export interface IApi {
  
}