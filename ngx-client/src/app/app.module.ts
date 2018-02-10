import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { ApiModule } from "./api/api.module";
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { FlexLayoutModule } from "@angular/flex-layout";

import { AppComponent } from './app.component';
import { MainContainerDirective } from './main-container.directive';
import { LayoutService } from "./layout.service";
import { MatModule } from "./mat/mat.module";
import { TemplatesModule } from "./templates/templates.module";
import { RoutesModule } from "./routes/routes.module";
import { MenuBarComponent } from './menu-bar/menu-bar.component';

@NgModule({
  declarations: [
    AppComponent,
    MainContainerDirective,
    MenuBarComponent
  ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    FlexLayoutModule,
    ApiModule,
    MatModule,
    TemplatesModule,
    RoutesModule
  ],
  exports: [
    ApiModule,
    FormsModule,
    ReactiveFormsModule,
    RoutesModule,
    TemplatesModule
  ],
  providers: [
    LayoutService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
