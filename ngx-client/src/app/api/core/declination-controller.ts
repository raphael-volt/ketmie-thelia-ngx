import {
    Component, Injectable,
    Input, Output, EventEmitter,
    OnInit, OnDestroy,
    OnChanges, SimpleChanges
} from "@angular/core";
import { DeclinationService } from "../declination.service";
import { IDeclination, IDeclinationItem, CardItem, ProductDetail, ProductDeclination } from "../api.model";
const styleUrl: string = './declination-controller.css'
@Component({
    selector: 'decli-base',
    template: '<ng-content></ng-content>'
})
export class DeclinationController implements OnChanges, OnInit, OnDestroy {

    @Output()
    declinationChange: EventEmitter<IDeclination> = new EventEmitter<IDeclination>()
    private _declination: IDeclination

    @Input()
    get declination(): IDeclination {
        return this._declination
    }
    set declination(value: IDeclination) {
        if (this._declination == value)
            return
        this._declination = value

        this.declinationChange.emit(value)
    }

    declinationItems: IDeclinationItem[] = []

    @Output()
    declinationItemChange: EventEmitter<IDeclinationItem> = new EventEmitter<IDeclinationItem>()
    private _declinationItem: IDeclinationItem

    @Input()
    get declinationItem(): IDeclinationItem {
        return this._declinationItem
    }
    set declinationItem(value: IDeclinationItem) {
        if (this._declinationItem == value)
            return
        this._declinationItem = value

        this.declinationItemChange.emit(value)
        console.log(this.constructor.name+"#"+this.id+" set declinationItem", value)
    }
    @Output()
    cardItemChange: EventEmitter<CardItem> = new EventEmitter<CardItem>()
    private _cardItem: CardItem

    @Input()
    get cardItem(): CardItem {
        return this._cardItem
    }
    set cardItem(value: CardItem) {
        if (this._cardItem == value)
            return
        this._cardItem = value

        this.cardItemChange.emit(value)
    }

    @Output()
    productChange: EventEmitter<ProductDetail> = new EventEmitter<ProductDetail>()
    private _product: ProductDetail

    @Input()
    get product(): ProductDetail {
        return this._product
    }
    set product(value: ProductDetail) {
        if (this._product == value)
            return
        this._product = value

        this.productChange.emit(value)
    }

    constructor(
        protected service: DeclinationService
    ) {
        this.id = String(DeclinationController.ID++)
        console.log(this.constructor.name+"#"+this.id, "constructor")
    }

    private _declinationId: string

    ngOnChanges(changes: SimpleChanges) {
        
        const currentId: string = this._declinationId
        const decli = this.service
        let itemChange = false
        let productChange = false
        if (changes.cardItem) {
            itemChange = true
        }
        if (changes.product) {
            productChange = true
        }

        let declinationChanged: boolean = false
        let declinationId: string = currentId
        let declination: IDeclination = this.declination

        let target: CardItem | ProductDetail = this.product || this.cardItem
        let item: CardItem = itemChange ? this.cardItem : null
        let product: ProductDetail = productChange ? this.product : null

        if (itemChange || productChange) {
            if (itemChange && !isWith(item, 'declinations')) {
                item = null
            }
            if (productChange && !isWith(product, 'declinations')) {
                product = null
            }
            if (product && item) {
                if (product.id != item.productId)
                    throw new EvalError('product.id must equals cardItem.productId')
            }
            target = product || item
            if (target) {
                declinationId = decli.findDeclination(target.declinations)
            }
        }

        if (changes.declination) {
            declinationId = decli.getId(declination)
        }

        if (declinationId != currentId) {
            this._declinationId = declinationId
            this.declination = decli.get(declinationId)
            this.declinationItems = decli.map(this.declination, 'si')
        }
        if (!declinationId && this.declinationItems.length)
            this.declinationItems.length = 0

        let decliItem: IDeclinationItem = this.declinationItem

        if (item)
            decliItem = decli.getItem(declinationId, item.decliId)
        else
            if (!isWith(decliItem, "id") || !decli.hasItem(declinationId, decliItem.id))
                decliItem = null


        if (decliItem != this.declinationItem) {
            this.declinationItem = decliItem
        }
    }

    static ID: number = 0
    private id: string

    modelChange(value: IDeclinationItem) {
        //this.declinationItem = value
    }

    ngOnInit() {

    }

    ngOnDestroy() {

    }


}

const isWith = <T extends Object, K extends keyof T>(v: T, p: K): v is T => {
    if (typeof v == 'object') {
        return v.hasOwnProperty(p)
    }
    return false
}


@Component({
    selector: 'decli-radio-group',
    templateUrl: './declination-radio-group.component.html',
    styleUrls: ['./declination-controller.css']
})
export class DeclinationRadioGroupComponent extends DeclinationController { }

@Component({
    selector: 'decli-select',
    templateUrl: './declination-select.component.html',
    styleUrls: ['./declination-controller.css']
})
export class DeclinationSelectComponent extends DeclinationController { }
