import { Pipe, PipeTransform } from '@angular/core';
import { CardItem, IDeclination, Product } from '@ngnx/thelia/model';
import { DeclinationService } from '@ngnx/thelia/api';
@Pipe({
  name: 'cardItemPrice',
  pure: false
})
export class CardItemPricePipe implements PipeTransform {
  constructor(private decli: DeclinationService) {}

  transform(value: CardItem, option?: 'quantity' | 'first' | 'last'): any {
    if (value) {
      let priceStr: string;
      const p = value.product;
      if (this.decli.declined(p)) {
        let decli = this.decli.getDeclination(p);
        let id = value.decliId;
        if (option == 'last' || option == 'first') {
          let l = this.decli.map(decli);
          const i = option == 'last' ? l.length - 1 : 0;
          id = l[i].id;
          return decli.items[l[i].id].price;
        }
        priceStr = decli.items[id].price;
      } else priceStr = p.price;

      if (option != 'quantity') return priceStr;
      return Number(priceStr) * value.quantity;
    }
    return null;
  }
}
