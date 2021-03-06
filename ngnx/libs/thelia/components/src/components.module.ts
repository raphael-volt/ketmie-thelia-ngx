import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FlexLayoutModule } from '@angular/flex-layout';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatCommonModule, MatRippleModule } from '@angular/material';
import { RouterModule } from '@angular/router';
import { TheliaCommonModule } from '@ngnx/thelia/common';
import { ApiImageDirective } from './api-image.directive';
import { ImgTileService } from './img-tile.service';
import { MatEnvModule } from '@ngnx/mat-env';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { KetmieBackgroundComponent } from './ketmie-background/ketmie-background.component';
import { CmsContentComponent } from './cms-content/cms-content.component';
import { CategoryComponent } from './category/category.component';
import { ProductComponent } from './product/product.component';
import { ImRowComponent } from './im-row/im-row.component';
import { CardComponent } from './card/card.component';
import { CardItemComponent } from './card/card-item/card-item.component';
import { SquareBoxDirective } from './square-box.directive';
import { InnerHtmlDirective } from './inner-html.directive';
import { SliderDirective } from './slider.directive';
import { ImgBoxDirective } from './product/img-box.directive';
import { TemplateDirective } from './template.directive';
import { CustomerComponent, BlurChildDirective } from './customer/customer.component';
import { ConnectionFormComponent } from './customer/connection-form/connection-form.component';
import { AddressComponent } from './address/address.component';
import { CountryPipe } from './country.pipe';
import { FillParentDirective } from './fill-parent.directive';
import { AddressViewComponent } from './address-view/address-view.component';
import { RaisonPipe } from './raison.pipe';
import { AddressModalComponent } from './address-modal/address-modal.component';
import { ApiModule } from '@ngnx/thelia/api';
import { MenuButtonComponent } from './menu-bar/menu-button/menu-button.component';
import {
  DeclinationController,
  DeclinationRadioGroupComponent,
  DeclinationSelectComponent
} from './declinations/declination-controller';
import { MenuBarComponent } from './menu-bar/menu-bar.component';
import { CardItemPricePipe } from './card-item-price.pipe';
import { CardTableComponent } from './card/card-table/card-table.component';
import { IconButtonComponent } from './menu-bar/icon-button/icon-button.component';
import { ExpandableContentComponent } from './expandable-content/expandable-content.component';
import { OrderStepperComponent } from './order-stepper/order-stepper.component';
@NgModule({
  imports: [
    MatCommonModule,
    MatRippleModule,
    BrowserAnimationsModule,
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    FlexLayoutModule,
    MatEnvModule,
    ApiModule,
    RouterModule,
    TheliaCommonModule
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
    ImRowComponent,
    BlurChildDirective,
    SquareBoxDirective,
    TemplateDirective,
    CustomerComponent,
    AddressComponent,
    CountryPipe,
    FillParentDirective,
    AddressViewComponent,
    RaisonPipe,
    AddressModalComponent,
    ConnectionFormComponent,
    CardComponent,
    CardItemComponent,
    DeclinationController,
    DeclinationRadioGroupComponent,
    DeclinationSelectComponent,
    MenuBarComponent,
    MenuButtonComponent,
    CardItemPricePipe,
    CardTableComponent,
    IconButtonComponent,
    ExpandableContentComponent,
    OrderStepperComponent
  ],
  exports: [
    KetmieBackgroundComponent,
    MenuBarComponent,
    MenuButtonComponent,
    TemplateDirective,
    ImRowComponent,
    SliderDirective,
    ImgBoxDirective,
    SquareBoxDirective,
    FillParentDirective,
    BlurChildDirective,
    ApiImageDirective,
    IconButtonComponent,
    ExpandableContentComponent
  ],
  entryComponents: [AddressComponent, AddressModalComponent, ConnectionFormComponent, CardComponent],
  providers: [ImgTileService]
})
export class ComponentsModule {}
