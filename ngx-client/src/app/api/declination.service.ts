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

  setMap(json: IDeclinationMap) {
    for(const i in json) {
      for(const j in json[i].items) {
        json[i].items[j].id = j
      }
      declinationMap[i] = json[i]
    }
  }
  has(id: string): boolean {
    return hasProperty(declinationMap, id)
  }

  declined(product: ProductDetail): boolean {
    const id = this.getProductDeclinationId(product)
    return id != null
  }

  findDeclination(declinations: string[]): string {
    if (declinations && declinations.length)
      for (const p of declinations)
        if (this.has(p))
          return p
    return null
  }

  getCardItemtDeclinationId(item: CardItem): string {
    return this.findDeclination(item.product.declinations)
  }

  getProductDeclinationId(product: ProductDetail): string {
    return this.findDeclination(product.declinations)
  }

  getDeclination(product: ProductDetail): IDeclination {
    const id = this.getProductDeclinationId(product)
    if (id)
      return this.get(id)
    return null
  }

  hasItem(id: string, itemId: string): boolean {
    return hasProperty(declinationMap, id) && hasProperty(declinationMap[id].items, itemId)
  }

  getId(d: IDeclination): string {
    for (const i in declinationMap)
    if(declinationMap[i] == d)
      return i
    return null
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
//<D, DI, K extends keyof DI>(v:D, p:K): DI[] =>

//  map<T extends Object, K extends keyof T>(declinations: IDeclination, sort?: K, numeric?: boolean): IDeclinationItem[] {
  map<K extends keyof IDeclinationItem>
  (declinations:IDeclination, sort?: K, numeric?: boolean): IDeclinationItem[] {
    const items: IDeclinationItem[] = []
    for (const id in declinations.items)
      items.push(declinations.items[id])
    if (sort) {
      this.sort(items, sort, numeric)
    }
    return items
  }

  sort<K extends keyof IDeclinationItem>(items: IDeclinationItem[], p: K, numeric?: boolean) {
    if(! p) {
      p = "size" as K
    }
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
