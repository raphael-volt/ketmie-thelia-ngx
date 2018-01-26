import { Component, ElementRef, AfterViewInit, OnDestroy, ViewChild, OnChanges, SimpleChanges } from '@angular/core';
import { ActivatedRoute } from "@angular/router";
import { ApiService } from "../../api/api.service";
import { LoaderEvent } from "../../shared/events/loader-event";
import { ImgTileService } from "../img-tile.service";
import { Subscription, Observable, Observer } from "rxjs";
import { DeactivableComponent } from "../../routes/deactivable.component";
@Component({
  selector: 'category',
  templateUrl: './category.component.html',
  styleUrls: ['./category.component.css']
})
export class CategoryComponent extends DeactivableComponent implements AfterViewInit, OnDestroy, OnChanges {

  @ViewChild('imgCtn')
  imgCtnRef: ElementRef
  imgCtn: HTMLElement
  categoryId: string
  loading: boolean = true
  constructor(
    private route: ActivatedRoute,
    private api: ApiService,
    private tile: ImgTileService) {
      super()
      console.log("CategoryComponent.constructor")
  }

  enabled: boolean = false
  deactivate(): Observable<boolean> {
    if(this.productId) {
      return Observable.of(true) 
    }
    return Observable.create((observer: Observer<boolean>)=>{

      this.enabled = false
      setTimeout(() => {
        observer.next(true)
        observer.complete()
      }, 300);
    })
  }

  showProduct(id: string) {
    console.log("showProduct", id)
  }

  category: any
  children: any[]
  categoryLabel: string = ""
  private productId: string
  private routeSubscription: Subscription
  ngAfterViewInit() {
    let sub1 = this.api.getShopCategories().subscribe(
      catalog => {
        this.routeSubscription = this.route.params.subscribe(params => {
          this.category = this.api.getCategoryById(params.id)
          if(params.productId) {
            console.log("params.productId", params.productId)
            this.productId = params.productId
          }
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
      if(! this.imgCtn)
      this.imgCtn = this.imgCtnRef.nativeElement
      
      this.tileId = "category" + this.category.id
      this.tile.create(this.tileId, this.imgCtn, this.events.map(e => e.target))
      this.events = []
      this.enabled = true
      this.percentLoaded = 0
    }
  }
  ngOnDestroy() {
    this.tile.destroy(this.tileId)
    this.routeSubscription.unsubscribe()
  }

}
