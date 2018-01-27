import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute, Router } from "@angular/router";
import { ApiService } from "../../api/api.service";
import { Category, ProductDetail, ProductDeclination } from "../../api/api.model";
import { Subscription } from "rxjs";
@Component({
  selector: 'product',
  templateUrl: './product.component.html',
  styleUrls: ['./product.component.css']
})
export class ProductComponent implements OnInit, OnDestroy {

  constructor(
    private api: ApiService,
    private route: ActivatedRoute,
    private router: Router,
  ) { }

  private routeSubscription: Subscription
  loaded: boolean
  declinationModel
  canAddToCard: boolean
  product: ProductDetail
  productPrice: string
  close() {
    let subscribed: boolean = false
    let sub = this.route.parent.url.subscribe(value => {
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
  prev() {
    
  }
  next() {

  }

  declinationChange(value) {
    let declination: ProductDeclination = this.product.declinations.find(d => {
      return d.id == value
    })
    this.productPrice = declination.price
  }

  ngOnInit() {
    let sub = this.route.parent.params.subscribe(params => {
      const categoryId: string = params.id
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
          this.loaded = true
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
    this.routeSubscription.unsubscribe()
  }
}
