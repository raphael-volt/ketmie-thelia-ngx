import { NgModule } from '@angular/core';
import { AppComponent } from './app.component';
import { BrowserModule } from '@angular/platform-browser';
import { NxModule } from '@nrwl/nx';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

import { TheliaCoreModule } from '@ngnx/thelia/core';

import { TweenExampleComponent } from './tween-example/tween-example.component';

@NgModule({
  imports: [BrowserModule, NxModule.forRoot(), BrowserAnimationsModule, TheliaCoreModule],
  exports: [BrowserAnimationsModule, TheliaCoreModule],
  declarations: [AppComponent, TweenExampleComponent],
  bootstrap: [AppComponent]
})
export class AppModule {}
