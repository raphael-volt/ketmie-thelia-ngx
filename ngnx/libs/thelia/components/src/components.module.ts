import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FlexLayoutModule } from '@angular/flex-layout';
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
import {
  DeclinationController,
  DeclinationRadioGroupComponent,
  DeclinationSelectComponent
} from './declinations/declination-controller';
import { MenuBarComponent } from './menu-bar/menu-bar.component';
import { CardItemPricePipe } from './card-item-price.pipe';
import { CardTableComponent } from './card/card-table/card-table.component';
@NgModule({
  imports: [
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
    CardItemPricePipe,
    CardTableComponent
  ],
  exports: [
    KetmieBackgroundComponent,
    MenuBarComponent,
    TemplateDirective,
    ImRowComponent,
    SliderDirective,
    ImgBoxDirective,
    SquareBoxDirective,
    FillParentDirective,
    BlurChildDirective,
    ApiImageDirective
  ],
  entryComponents: [AddressComponent, AddressModalComponent, ConnectionFormComponent, CardComponent],
  providers: [ImgTileService]
})
export class ComponentsModule {}
