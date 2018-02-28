import { Pipe, PipeTransform } from '@angular/core';
import { IDeclinationItem } from '@ngnx/thelia/model';
@Pipe({
  name: 'declination'
})
export class DeclinationPipe implements PipeTransform {
  transform(value: IDeclinationItem, mode: 'price' | 'size' | 'all' = 'all'): any {
    let i: IDeclinationItem = value;
    let result = null;
    if (value) {
      switch (mode) {
        case 'price':
          result = value.price;
          break;

        case 'size':
          result = value.size;
          break;

        case 'all':
          result = `${value.size} cm ${value.price} €`;
          break;

        default:
          break;
      }
    }
    return result;
  }
}
