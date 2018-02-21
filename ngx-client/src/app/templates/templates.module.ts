import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FlexLayoutModule } from "@angular/flex-layout";

import { ApiImageDirective } from './api-image.directive';
import { ImgTileService } from "./img-tile.service";
import { MatModule } from "../mat/mat.module";
import { RoutesModule } from "../routes/routes.module";
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { KetmieBackgroundComponent } from './ketmie-background/ketmie-background.component';
import { CmsContentComponent } from './cms-content/cms-content.component';
import { CategoryComponent } from './category/category.component';
import { ProductComponent } from './product/product.component';
import { ImRowComponent } from './im-row/im-row.component';
import { ThemeComponent } from './theme/theme.component';

import { SquareBoxDirective } from './square-box.directive';
import { InnerHtmlDirective } from './inner-html.directive';
import { SliderDirective } from "./slider.directive";
import { ImgBoxDirective } from './product/img-box.directive';
import { TemplateDirective } from './template.directive';
import { FakeCardComponent } from './fake-card/fake-card.component';
import { CustomerComponent, BlurChildDirective } from './customer/customer.component';
import { ConnectionFormComponent } from './customer/connection-form/connection-form.component';
import { AddressComponent } from './address/address.component';
import { CountryPipe } from './country.pipe';
import { FillParentDirective } from './fill-parent.directive';
import { AddressViewComponent } from './address-view/address-view.component';
import { RaisonPipe } from './raison.pipe';
import { AddressModalComponent } from './address-modal/address-modal.component';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    FlexLayoutModule,
    MatModule,
    RoutesModule,
  ],
  declarations: [
    SliderDirective,
    InnerHtmlDirective,
    CategoryComponent,
    KetmieBackgroundComponent,
    ApiImageDirective,
    CmsContentComponent,
    ProductComponent,
    ImgBoxDirective,
    ThemeComponent,
    ImRowComponent,
    BlurChildDirective,
    SquareBoxDirective, 
    TemplateDirective, 
    FakeCardComponent, 
    CustomerComponent, 
    ConnectionFormComponent, 
    AddressComponent, 
    CountryPipe, 
    FillParentDirective, 
    AddressViewComponent, 
    RaisonPipe, 
    AddressModalComponent
  ],
  exports: [
    KetmieBackgroundComponent,
    TemplateDirective,
    ImRowComponent,
    SliderDirective,
    ImgBoxDirective,
    SquareBoxDirective,
    FillParentDirective,
    BlurChildDirective
  ],
  entryComponents: [
    ConnectionFormComponent,
    AddressComponent,
    AddressModalComponent
  ],
  providers: [ImgTileService]
})
export class TemplatesModule { }
