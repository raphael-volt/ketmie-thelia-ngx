import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { PopupService } from './popup.service';
import { SnackBarService } from './snack-bar.service';
import { DialogContentComponent } from './components/dialog-content/dialog-content.component';
import { SnackBarViewComponent } from './components/snack-bar-view/snack-bar-view.component';
import { ImgLoaderService } from './img-loader.service';
import { MatEnvModule } from '@ngnx/mat-env';
import { ApiModule } from '@ngnx/thelia/api';
import { CustomerEmailDirective } from './directives/customer-email.directive';
import { DeclinationPipe } from './directives/declination.pipe';
import { SliderDirective } from './directives/slider.directive';
import { LayoutService } from './layout.service';
@NgModule({
  imports: [CommonModule, MatEnvModule, ApiModule, FormsModule, ReactiveFormsModule],
  declarations: [
    SnackBarViewComponent,
    DialogContentComponent,
    DeclinationPipe,
    CustomerEmailDirective,
    SliderDirective
  ],
  exports: [DeclinationPipe, CustomerEmailDirective, SliderDirective],
  providers: [SnackBarService, PopupService, ImgLoaderService, LayoutService],
  entryComponents: [SnackBarViewComponent, DialogContentComponent]
})
export class TheliaCommonModule {}
