import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FlexLayoutModule } from "@angular/flex-layout";
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MatModule } from "../../mat/mat.module";
import { ApiModule } from "../../api/api.module";
import { CardComponent } from "./card.component";
import { TemplatesModule } from "../templates.module";
import { CartItemComponent } from './cart-item/cart-item.component';
@NgModule({
  imports: [
    CommonModule,
    TemplatesModule,
    MatModule, 
    FlexLayoutModule, 
    FormsModule, 
    ReactiveFormsModule,
    ApiModule
  ],
  declarations: [
    CardComponent,
    CartItemComponent
  ],
  exports: [
    CardComponent
  ]
})
export class CardModule { }
