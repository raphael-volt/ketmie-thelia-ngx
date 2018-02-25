import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

@NgModule({
  imports: [
    CommonModule
  ]
})
export class Api2Module { }

const api2 = () => {
  return "Api2Module"
}

export { api2 }