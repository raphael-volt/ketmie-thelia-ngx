import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { LocalStorageModule } from 'angular-2-local-storage'

import { ApiModule } from "./api/api.module";
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
//import { FlexLayoutModule } from "@angular/flex-layout";

import { AppComponent } from './app.component';
import { MainContainerDirective } from './main-container.directive';
import { LayoutService } from "./layout.service";
import { MatModule } from "./mat/mat.module";
import { TemplatesModule } from "./templates/templates.module";
import { RoutesModule } from "./routes/routes.module";
import { MenuBarComponent } from './menu-bar/menu-bar.component';
import { DialogContentComponent } from './shared/dialog-content/dialog-content.component';
import { PopupService } from "./popup.service";
import { SnackBarService } from "./snack-bar.service";
import { SnackBarViewComponent } from './snack-bar-view/snack-bar-view.component';
import { CardModule } from "./templates/card/card.module";

import { ModelModule } from "@thelia/model";
import { Api2Module } from "@thelia/api";
// import { Api2Module } from "@thelia/api";

@NgModule({
  declarations: [
    AppComponent,
    MainContainerDirective,
    MenuBarComponent,
    DialogContentComponent,
    SnackBarViewComponent
  ],
  imports: [
    ModelModule,
    Api2Module,
    BrowserModule,
    BrowserAnimationsModule,
    LocalStorageModule,
    //FlexLayoutModule,
    ApiModule,
    MatModule,
    TemplatesModule,
    CardModule,
    RoutesModule
  ],
  exports: [
    ModelModule,
    ApiModule,
    FormsModule,
    LocalStorageModule,
    ReactiveFormsModule,
    RoutesModule,
    TemplatesModule,
    CardModule
  ],
  providers: [
    LayoutService,
    PopupService,
    SnackBarService
  ],
  entryComponents: [
    DialogContentComponent,
    SnackBarViewComponent
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
