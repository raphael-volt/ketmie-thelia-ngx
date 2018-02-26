import { Injectable, EventEmitter } from '@angular/core';
import { ApiService } from "./api.service";
import { RequestService } from "./request.service";
import { DeclinationService } from "./declination.service";
import {
  APIResponse,
  Card, ICard, serializableCardItem,
  declinationMap, IDeclinationMap, IDeclination, IDeclinationItem,
  CardItem, CardItemPerso,
  ProductDetail
} from "./api.model";
import { one } from "../shared/utils/subscription.utils";
import { ListBase } from "../shared/utils/list-base";
export type CardAction = "get" | "add" | "remove" | "update" | "clear"
export type CardEvent = {
  card: Card,
  cardItem?: CardItem,
  action: CardAction
}
const createCardEvent = (m: CardAction, card: Card, item?: CardItem): CardEvent => {
  return {
    card: card,
    cardItem: item,
    action: m
  }
}
export type ICNext<T> = (t: T) => void
export type ICNextK<T> = (k: T, next?: ICNext<T>) => void

export type ICardImpl<T, K> = {
  numArticles: number
  numItems: number
  total: number
  card: T
  at(index: number): K
  get(next: ICNext<T>): void
  clear(next: ICNext<T>): void
  refresh(next: ICNext<T>): void
  add(item: K, next: ICNext<K>): void
  remove(item: K, next: ICNext<K>): void
  update(item: K, next: ICNext<K>): void
}


@Injectable()
export class CardService implements ICardImpl<Card, CardItem> {

  events: EventEmitter<CardEvent> = new EventEmitter<CardEvent>(true)

  isEmptyChanged: EventEmitter<boolean> = new EventEmitter<boolean>()
  private _isEmpty: boolean = true

  get isEmpty(): boolean {
    return this._isEmpty
  }
  private checkEmpty() {
    let e = this.numItems == 0
    if (this._isEmpty != e) {
      this._isEmpty = e
      this.isEmptyChanged.emit(e)
    }
    console.log("checkEmpty", this._isEmpty, this.numItems)

  }
  private _card: Card = undefined
  get card(): Card {
    return this._card
  }

  get hasCard(): boolean {
    return this._card != undefined
  }

  constructor(
    public api: ApiService,
    public request: RequestService,
    private declinationService: DeclinationService
  ) {

  }

  private init = (next?) => { }

  get numArticles(): number {
    return this._card ? this._card.numArticles : 0
  }
  get numItems(): number {
    return this._card ? this._card.numItems : 0
  }

  get total(): number {
    return this._card ? this._card.total : 0
  }

  private list: ListBase<CardItem> = new ListBase<CardItem>()

  get length() {
    return this._card ? this.list.length : 0
  }

  private decliMap: IDeclinationMap

  get(next) {
    if (this._card)
      return next(this._card)
    this.getCard(next)
  }

  private updateMap: boolean
  private getCardRequest() {
    const p: any = { cardAction: "get" }
    if (!this.decliMap) {
      this.updateMap = true
      p.map = 1
    }
    return this.api.get(this.request.getRequest(
      this.request.getSearchParam("card", p)
    ))
  }

  private getCardMap: any[]
  private getCard(next) {
    if (!this.getCardMap)
      this.getCardMap = []
    const l = this.getCardMap
    l.push(next)
    if (l.length == 1) {
      one(this.getCardRequest(),
        response => {
          const result = response.body
          const c = this._card

          if (this.updateMap) {
            this.updateMap = false
            this.declinationService.setMap(result.map)
          }
          let data = { items: result.data, total: result.total }
          if (!this._card) {
            this._card = new Card(data)
          }
          else
            this._card.update(data)
          this.list.items = this._card.items
          this.validateProperties(result.total)
          this.notify("get")
          for (let n of this.getCardMap)
            n(this._card)
          this.getCardMap = undefined
          this.checkEmpty()
        }
      )
    }
  }

  createItem(product: ProductDetail, decliId?: any, q: number = 1) {
    let res: CardItem = {
      product: { id: product.id },
      quantity: q
    }
    if (this.declinationService.declined(product)) {
      if (this.declinationService.hasItem(product.declinations[0], decliId)) {
        res.decliId = decliId
      }
    }
    return res
  }

  add(item: CardItem, next) {
    this.checkCard
    this.one("add", item, resopnse => {
      item.index = this.list.add(item) - 1
      this.validateProperties(resopnse.body.total)
      this.notify("add", item)
      this.checkEmpty()
      next(item)
    })

  }

  update(item: CardItem, next: (item: CardItem) => void) {
    this.checkCard
    this.one("update", item, (resopnse: APIResponse) => {
      this.validateProperties(resopnse.body.total)
      this.notify("update", item)
      next(item)
    })
  }

  remove(item, next) {
    this.checkCard
    const l = this.list
    const i = l.index(item)
    if (i < 0)
      return next(null)

    this.one("remove", { index: item.index }, resopnse => {
      l.remove(item)
      item.index = -1
      item.quantity = 0
      const n = l.length
      for (var j = i; j < n - 1; j++)
        l.at(j).index = j;
      this.validateProperties(resopnse.body.total)
      this.notify("remove", item)
      this.checkEmpty()
      next(item)

    })
  }

  clear(next) {
    this.checkCard
    this.one("clear", null, response => {
      const result = response.body
      this.card.items.length = 0
      this.list.items = this.card.items
      this.validateProperties(0)
      this.notify("clear")
      this.checkEmpty()
      next(this.card)
    }
    )
  }

  at(index: number): CardItem {
    this.checkCard
    return this.list.at(index)
  }

  index(item: CardItem): number {
    this.checkCard
    return this.list.index(item)
  }

  refresh<T extends Card>(next: ICNext<T>) {
    return this.getCard(next)
  }

  private notify(action: CardAction, item?: CardItem) {
    this.events.emit(
      createCardEvent(action, this._card, item)
    )
  }

  private validateProperties(total: any) {
    if (typeof total != "number")
      total = Number(total)
    if (!total)
      total = 0

    this._card.total = Number(total)
  }

  private getRequest(
    cardAction: CardAction,
    body?: CardItem) {
    return this.request.getRequest(
      this.request.getSearchParam(
        "card", { cardAction: cardAction }
      ), body
    )
  }

  private checkQuantity(item: CardItem): void {
    if (!item.quantity)
      item.quantity = 0
    return
  }

  private get checkCard(): void {
    if (!this.card)
      throw new Error("card is undefined");
    return
  }

  private one(action: CardAction, item: CardItem, next: ICNext<APIResponse>) {
    one(
      this.api.post(
        this.getRequest(action, item)),
      next
    )
  }
}
