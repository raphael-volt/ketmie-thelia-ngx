import { Injectable } from '@angular/core';
import {
  declinationMap, IDeclination, IDeclinationItem, IDeclinationMap,
  ProductDetail, CardItem
} from "./api.model";
const sortString = (a: string, b: string): number => {
  return a > b ? 1 : -1
}
const sortNum = (a: string, b: string): number => {
  return Number(a) - Number(b)
}
const hasProperty = (i: { [j: string]: any }, p: string): boolean => {
  return i.hasOwnProperty(p)
}
@Injectable()
export class DeclinationService {

  constructor() { }

  has(id: string): boolean {
    return hasProperty(declinationMap, id)
  }

  declined(product: ProductDetail): boolean {
    const id = this.getProductDeclinationId(product)
    return id != null
  }

  getProductDeclinationId(product: ProductDetail): string {
    if (product.declinations && product.declinations.length)
      for (const i of product.declinations)
        if (this.has(i))
          return i
    return null
  }

  getDeclination(product: ProductDetail): IDeclination {
    const id = this.getProductDeclinationId(product)
    if(id) 
      return this.get(id)
    return null
  }

  hasItem(id: string, itemId: string): boolean {
    return hasProperty(declinationMap, id) && hasProperty(declinationMap[id].items, itemId)
  }

  get(id: string): IDeclination {
    if (this.has(id))
      return declinationMap[id]
    return null
  }

  getItem(id: string, itemId): IDeclinationItem {
    if (this.hasItem(id, itemId))
      return declinationMap[id].items[itemId]

    return null
  }

  map(declinations: IDeclination, sort?: string, numeric?: boolean): IDeclinationItem[] {
    const items: IDeclinationItem[] = []
    for (const id in declinations.items)
      items.push(Object.assign({ id: id }, declinations.items[id]))
    if (sort) {
      this.sort(items, sort, numeric)
    }
    return items
  }

  sort(items: IDeclinationItem[], p: string, numeric?: boolean) {
    const f = numeric ? sortNum : sortString
    items.sort((a, b) => {
      return f(a[p], b[p])
    })
  }

  sortBySize(declinations: IDeclination) {
    return this.map(declinations, 'size', true)
  }

  sortByPrice(declinations: IDeclination) {
    return this.map(declinations, 'price', true)
  }
}
