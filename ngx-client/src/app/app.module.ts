import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { ApiModule } from "./api/api.module";
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { AppComponent } from './app.component';
import { MainContainerDirective } from './main-container.directive';
import { LayoutService } from "./layout.service";
import { MatModule } from "./mat/mat.module";
import { TemplatesModule } from "./templates/templates.module";
import { RoutesModule } from "./routes/routes.module";

@NgModule({
  declarations: [
    AppComponent,
    MainContainerDirective
  ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
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
