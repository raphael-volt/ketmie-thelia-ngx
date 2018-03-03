import { Pipe, PipeTransform } from '@angular/core';
import { ApiService } from "./api.service";
@Pipe({
  name: 'productUrl'
})
export class ProductUrlPipe implements PipeTransform {
  constructor(private api: ApiService) {

  }
  transform(productId: string): any {
    const cId = this.api.getProductCategory(productId)
    if(cId)
      return `/category/${cId}/product/${productId}`
    return null;
  }
}
