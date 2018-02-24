import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CardComponent } from "./card.component";
import { TemplatesModule } from "../templates.module";
@NgModule({
  imports: [
    CommonModule,
    TemplatesModule
  ],
  declarations: [
    CardComponent
  ],
  exports: [
    CardComponent
  ]
})
export class CardModule { }
