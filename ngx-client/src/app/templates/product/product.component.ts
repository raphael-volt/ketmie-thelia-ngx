import { Component, OnInit, OnDestroy, ViewChild } from '@angular/core';
import { ActivatedRoute, Router } from "@angular/router";
import { ApiService, findCategoryIdInURL, replaceProductIdInURL } from "../../api/api.service";
import { Category, Product, ProductDetail, ProductDeclination } from "../../api/api.model";
import { Subscription, Observable, Observer } from "rxjs";
import { SliderBaseComponent } from "../slider-base.component";
import { ImgBoxDirective } from "./img-box.directive";
import { SliderEvent } from "../slider.directive";
@Component({
  selector: 'product',
  templateUrl: './product.component.html',
  styleUrls: ['./product.component.css']
})
export class ProductComponent extends SliderBaseComponent implements OnInit, OnDestroy {

  @ViewChild(ImgBoxDirective)
  imgBox: ImgBoxDirective

  boxProduct: ProductDetail = undefined

  loaded: boolean
  declinationModel
  canAddToCard: boolean
  product: ProductDetail
  productPrice: string

  private routeSubscription: Subscription

  constructor(
    private api: ApiService,
    private route: ActivatedRoute,
    private router: Router,
  ) {
    super()
  }

  protected slideInEnded() {
    this.boxProduct = this.product
  }

  private getNearestProduct(id: string, dir: number): Product {
    const c: Category = this.api.getCategoryById(this.parentCategoryId)
    if (!c)
      return null
    const products = c.children
    let p = products.find(p => p.id == id)
    if (!p)
      return null

    let i: number = products.indexOf(p)
    p = null
    if (i != -1) {
      i += dir
      const n = products.length
      if (i > n - 1)
        i = 0
      if (i < 0)
        i = n - 1
      p = products[i]
    }
    return p
  }
/*
  deactivate(): Observable<boolean> {
    if (!this._productIdChanged)
    return super.deactivate()
  }
  */
  protected _deactivate() {
    if (!this._productIdChanged)
      return super._deactivate()
    this.setDeactivable()
  }

  private _productIdChanged: boolean = false
  private navigateToNearestProduct(dir: number) {
    let url = this.router.url
    this.parentCategoryId = findCategoryIdInURL(url)
    const p = this.getNearestProduct(this.product.id, dir)
    if (p) {
      this._productIdChanged = true
      this.productPrice = undefined
      this.declinationModel = undefined
      url = replaceProductIdInURL(url, p.id)
      this.router.navigate([url])
    }
  }

  nextProduct() {
    this.navigateToNearestProduct(1)
  }

  prevProduct() {
    this.navigateToNearestProduct(-1)
  }

  close() {
    this._productIdChanged = false
    let subscribed: boolean = false
    let sub: Subscription = null
    let done = () => {
      subscribed = false
      sub = null
      sub = this.route.parent.url.subscribe(value => {
        if (sub) {
          sub.unsubscribe()
          sub = null
        }
        if (!subscribed) {
          subscribed = true
          let l = value.map(segment => String(segment.path))
          this.router.navigate(l)
        }
      })
      if (subscribed && sub)
        sub.unsubscribe()
    }

    if (this.imgBox) {
      sub = this.imgBox.close()
        .subscribe(v => {
          subscribed = true
          if (sub) {
            sub.unsubscribe()
            sub = null
          }
          done()
        })
      if (subscribed && sub)
        sub.unsubscribe()
    }
    else
      done()

  }


  declinationChange(value) {
    console.log("declinationChange", value, this.declinationModel)
    let declination: ProductDeclination = this.product.declinations.find(d => {
      return d.id == value
    })
    this.productPrice = declination.price
  }

  private parentCategoryId: string


  ngOnInit() {
    let sub = this.route.parent.params.subscribe(params => {
      this.parentCategoryId = findCategoryIdInURL(this.router.url)
      this.routeSubscription = this.route.params.subscribe(params => {
        let apiSub = this.api.getProductDetails(params.id).subscribe(product => {
          if (product.description == "")
            product.description = null
          if (!product.declinations || (product.declinations && !product.declinations.length))
            product.declinations = null

          if (!product.declinations)
            this.productPrice = product.price
          this.canAddToCard = product.declinations == null
          this.product = product
          if (this.sliderState == "none") {
            this.slideIn()
          }
          else
            this.boxProduct = product
        })
      })
      if (sub) {
        sub.unsubscribe()
        sub = null
      }
    })
    if (sub && !sub.closed) {
      sub.unsubscribe()
    }
  }

  ngOnDestroy() {
    if (this.routeSubscription)
      this.routeSubscription.unsubscribe()
  }

}
