import { Component, OnInit, OnDestroy } from '@angular/core';
import { Subscription } from "rxjs";
import { ApiService } from '@ngnx/thelia/api'

import { ShopTree, CMSContent, Category } from "@ngnx/thelia/model";
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit, OnDestroy {

  constructor(private apiService: ApiService) { }

  categories: Category[] = []
  cmsContents: CMSContent[] = []

  private treeSub: Subscription

  ngOnInit() {
    const api = this.apiService
    if (api.initialized) {
      this.loadTree()
    }
    else {
      let sub = api.initializedChange.subscribe(initialized => {
        sub.unsubscribe()
        this.loadTree()
      })
    }
  }

  private loadTree = (initialized?) => {
    this.treeSub = this.apiService.getShopTree().subscribe(shop => {
      this.categories = shop.shopCategories
      this.cmsContents = shop.cmsContents
    })
  }

  ngOnDestroy() {
    this.treeSub.unsubscribe()
  }
}
