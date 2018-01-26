import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute, Router } from "@angular/router";
import { ApiService } from "../../api/api.service";
import { Category, Product } from "../../api/api.model";
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
  private product: Product
  label: string
  loaded: boolean
  
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

  ngOnInit() {
    let sub = this.route.parent.params.subscribe(params => {
      const categoryId: string = params.id
      this.routeSubscription = this.route.params.subscribe(params => {
        let apiSub = this.api.getShopTree().subscribe(shop => {
          const category: Category = this.api.getCategoryById(categoryId)
          this.product = category.children.find(p => {
            return p.id == params.id
          })
          this.label = this.product.label
          this.loaded = true
          if (apiSub) {
            apiSub.unsubscribe()
          }
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
