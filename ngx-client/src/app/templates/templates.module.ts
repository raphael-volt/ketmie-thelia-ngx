import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FlexLayoutModule } from "@angular/flex-layout";

import { CategoryComponent } from './category/category.component';
import { ApiImageDirective } from './api-image.directive';
import { ImgTileService } from "./img-tile.service";
import { KetmieBackgroundComponent } from './ketmie-background/ketmie-background.component';
import { MatModule } from "../mat/mat.module";
import { CmsContentComponent } from './cms-content/cms-content.component';
import { ProductComponent } from './product/product.component';
import { RoutesModule } from "../routes/routes.module";
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { InnerHtmlDirective } from './inner-html.directive';
import { SliderDirective } from "./slider.directive";
import { ImgBoxDirective } from './product/img-box.directive';
import { ThemeComponent } from './theme/theme.component';

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
    ThemeComponent
  ],
  exports:[
    KetmieBackgroundComponent,
    SliderDirective,
    ImgBoxDirective
  ],
  providers: [ImgTileService]
})
export class TemplatesModule { }
