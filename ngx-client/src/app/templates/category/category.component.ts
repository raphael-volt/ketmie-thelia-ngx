import { Component, ElementRef, AfterViewInit, OnDestroy, ViewChild, OnChanges, SimpleChanges } from '@angular/core';
import { ActivatedRoute, Router, RouterEvent, NavigationEnd, NavigationStart } from "@angular/router";
import { ApiService } from "../../api/api.service";
import { LoaderEvent } from "../../shared/events/loader-event";
import { ImgTileService } from "../img-tile.service";
import { LayoutService, getMaxScreenWidth } from "../../layout.service";
import { Subscription, Observable, Observer } from "rxjs";
import { SliderBaseComponent } from "../slider-base.component";
import { SubscriptionCollector } from "../../shared/utils/subscription.utils";
@Component({
  selector: 'category',
  templateUrl: './category.component.html',
  styleUrls: ['./category.component.scss']
})
export class CategoryComponent extends SliderBaseComponent implements AfterViewInit, OnDestroy, OnChanges {

  @ViewChild('imgCtn')
  imgCtnRef: ElementRef
  imgCtn: HTMLElement
  categoryId: string
  loading: boolean = true
  imgHeight = 350
  
  
  private aliasChanged = (alias) => {
    let w = getMaxScreenWidth()
    // w * r = x
    // 1920 * r = 350
    // r = 350 / 1920
    // r = 0,182291667
    // r1280 = 0,2734375
    this.imgHeight = Math.ceil(.15 * w)
  }
  private subColl: SubscriptionCollector = new SubscriptionCollector()
  constructor(
    layout: LayoutService,
    private route: ActivatedRoute,
    private router: Router,
    private api: ApiService,
    private tile: ImgTileService) {
    super()
    this.subColl.add = router.events.subscribe((event: RouterEvent) => {
      if(event instanceof NavigationStart) {
        this.checkHasProduct(event.url)
      }
      
    })
    this.subColl.add = layout.responsiveAliasChange
      .subscribe(this.aliasChanged)
    this.aliasChanged(layout.responsiveAlias)
    this.checkHasProduct(router.url)
  }

  private checkHasProduct(url: string) {
    this.selectedProduct = /\/product\/(\d+)/.test(url)
  }

  category: any
  children: any[]
  categoryLabel: string = ""
  selectedProduct: boolean = false
  private productId: string
  private routeSubscription: Subscription
  ngAfterViewInit() {
    let sub1 = this.api.getShopCategories().subscribe(
      catalog => {
        this.routeSubscription = this.route.params.subscribe(params => {
          this.category = this.api.getCategoryById(params.id)
          this.categoryId = this.category.id
          this.categoryLabel = this.category.label
          if (this.tileId) {
            this.loading = true
            this.tile.destroy(this.tileId)
          }
          this.children = this.category.children
        },
          e => { },
          () => {

          })
      },
      e => { },
      () => {
        if (sub1) {
          sub1.unsubscribe()
          sub1 = null
        }
      })
    if (sub1 && sub1.closed) {
      sub1.unsubscribe()
    }
  }
  ngOnChanges(changes: SimpleChanges) {

  }

  private events: LoaderEvent[] = []
  private tileId: string
  percentLoaded: number = 0
  imgLoad(event: LoaderEvent) {
    if (this.events.indexOf(event) == -1) {
      this.events.push(event)
    }
    const n: number = this.category.children.length
    let loaded: number = 0
    for (event of this.events) {
      if (event.complete)
        loaded++
    }
    this.percentLoaded = loaded / n * 100
    // console.log(loaded, n)
    if (n == loaded) {
      this.loading = false
      if (!this.imgCtn)
        this.imgCtn = this.imgCtnRef.nativeElement

      this.tileId = "category" + this.category.id
      this.tile.create(this.tileId, this.imgCtn, this.events.map(e => e.target))
      this.events = []
      this.slideIn()
      this.percentLoaded = 0
    }
  }
  ngOnDestroy() {
    this.tile.destroy(this.tileId)
    this.routeSubscription.unsubscribe()
    this.subColl.unsubscribeAll()
  }

}
