import { NgModule } from '@angular/core';
import { CommonModule as NGCommonModule } from '@angular/common';

import { MatDialogModule } from "@angular/material";

import { PopupService } from './popup.service';
import { SnackBarService } from './snack-bar.service';
import { ImgTileService } from './core/img-tile.service';

import { DialogContentComponent } from "./dialog/dialog-content.component";
import { SnackBarViewComponent } from "./snack-bar/snack-bar-view.component";
import { SliderBaseComponent } from "./core/slider-base.component";


import { DeclinationPipe } from './core/declination.pipe';
import { RaisonPipe } from "./core/raison.pipe";
import { ApiImageDirective } from './core/api-image.directive';
import { CustomerEmailDirective } from './core/customer-email.directive';
import { DeactivableComponent } from './core/deactivable.component';
import { FillParentDirective } from './core/fill-parent.directive';
import { InnerHtmlDirective } from './core/inner-html.directive';
import { TemplateDirective } from "./core/template.directive";
import {
    DeclinationController,
    DeclinationRadioGroupComponent,
    DeclinationSelectComponent
} from "./core/declination-controller";

@NgModule({
    imports: [NGCommonModule, MatDialogModule],
    exports: [
        DeclinationRadioGroupComponent,
        DeclinationSelectComponent,
        SliderBaseComponent,
        DeclinationPipe,
        RaisonPipe,
        ApiImageDirective,
        CustomerEmailDirective,
        DeactivableComponent,
        FillParentDirective,
        InnerHtmlDirective,
        TemplateDirective
    ],
    declarations: [
        DeclinationRadioGroupComponent,
        DeclinationSelectComponent,
        SliderBaseComponent,
        DeclinationPipe,
        RaisonPipe,
        ApiImageDirective,
        CustomerEmailDirective,
        DeactivableComponent,
        FillParentDirective,
        InnerHtmlDirective,
        TemplateDirective
    ],
    providers: [
        PopupService, 
        SnackBarService, 
        ImgTileService],
    entryComponents: [
        DialogContentComponent,
        SnackBarViewComponent
    ]

})
export class CommonModule {

}