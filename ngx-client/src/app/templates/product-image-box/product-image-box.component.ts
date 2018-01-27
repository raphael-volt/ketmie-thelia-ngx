import {
  Component, AfterViewInit, OnDestroy,
  ElementRef, ViewChild,
  Input, OnChanges, SimpleChanges
} from '@angular/core';
import { Observer, Observable, Subscription } from "rxjs";
import { ApiService, ImgTypes } from "../../api/api.service";
import { LayoutService, ResponsiveAliases, LayoutSizes } from "../../layout.service";

import { XHRImage } from "../../shared/utils/xhr-image";
import { Rect } from "../../shared/math/rect";
import { Point } from "../../shared/math/point";
import { Tween, TweenEvent, TweenEventType, Quadratic, Ease, Linear, interpolate } from "../../shared/tween/ease";
import { clearCanvas, drawToCanvas, getContext, draw } from "../../shared/util/canvas.util";
@Component({
  selector: 'product-image-box',
  templateUrl: './product-image-box.component.html',
  styleUrls: ['./product-image-box.component.css']
})
export class ProductImageBoxComponent implements AfterViewInit, OnChanges, OnDestroy {

  constructor(
    private api: ApiService,
    private layout: LayoutService
  ) { }

  @ViewChild("canvas")
  canvasRef: ElementRef | undefined
  private _canvas: HTMLCanvasElement
  private _ctx: CanvasRenderingContext2D

  @Input()
  imageIds: string[]

  private images: HTMLImageElement[]

  private urls: string[]
  private imgWidth: number
  private currentIndex: number

  private sizes: LayoutSizes
  private layoutSub: Subscription
  private resposiveSub: Subscription

  next() {
    this.dir = -1
    const prev = this.currentIndex
    if (this.currentIndex == this.urls.length - 1)
      this.currentIndex = 0
    else
      this.currentIndex++
    this.checkLoad(prev, this.currentIndex)
  }

  prev() {
    this.dir = 1
    const prev = this.currentIndex
    if (this.currentIndex == 0)
      this.currentIndex = this.urls.length - 1
    else
      this.currentIndex--
    this.checkLoad(prev, this.currentIndex)
  }
  ngOnChanges(changes: SimpleChanges) {
    if (changes.imageIds) {
      let ids: string[] = changes.imageIds.currentValue
      if (ids && ids.length) {
        const alias = this.layout.responsiveAlias
        this.validateImgWidth(alias)
        this.updateUrls(alias)
        this.images = []
        if(this._canvas) {
          this.currentIndex = 0
          this.checkLoad(NaN, 0)
        }
      }
    }
  }
  ngAfterViewInit() {
    this.layoutSub = this.layout.layoutChange.subscribe((layout: LayoutSizes) => {
      this.sizes = layout
      this.beforeDraw()
    })
    this.resposiveSub = this.layout.responsiveAliasChange.subscribe((alias: ResponsiveAliases) => {
      if (this.validateImgWidth(alias)) {
        this.updateUrls(alias)
        this.beforeDraw()
      }
    })
    this._canvas = this.canvasRef.nativeElement
    this._ctx = getContext(this._canvas, true)
    this.sizes = this.layout.layout
    this.validateImgWidth(this.layout.responsiveAlias)
    this.updateCanvasSize()
    this._tween = new Tween(new Ease(0, 1, 20, Quadratic.out))
    if(this.urls) {
      this.currentIndex = 0
      this.checkLoad(NaN, 0)
    }
  }
  private beforeDraw() {
    clearCanvas(this._ctx, this._canvas)
    this.updateCanvasSize( )
    this.draw(false)
  }
  private updateCanvasSize( ) {
    if(! this._canvas || ! this.sizes)
      return
    const ww: number = this.sizes[0]
    const wh: number = this.sizes[1]
    let square: number = wh - 100
    if (square > ww)
      square = ww - this.hSpace
    console.log("updateCanvasSize", square)
    this._canvas.width = this._canvas.height = square
  }

  private updateUrls(alias: ResponsiveAliases) {
    if (!this.imageIds)
      return
    const api = this.api
    const w = this.imgWidth
    this.images = []
    this.urls = this.imageIds.map(id => {
      return api.getImageUrl(id, "produit", w)
    })
  }

  private hSpace: number = 350

  private validateImgWidth(alias: ResponsiveAliases): boolean {
    let newWidth: number
    let hSpace: number = this.hSpace
    switch (alias) {
      case ResponsiveAliases.XS:
        newWidth = 600-hSpace
        break;

      case ResponsiveAliases.SM:
        newWidth = 960-hSpace

        break;

      case ResponsiveAliases.MD:
        newWidth = 1280-hSpace

        break;

      case ResponsiveAliases.XL:
      case ResponsiveAliases.LG:
        newWidth = 1280 // max

        break;

      default:
        break;
    }
    if (this.imgWidth != newWidth) {
      this.imgWidth = newWidth
      return true
    }
    return false
  }

  ngOnDestroy() {
    this.layoutSub.unsubscribe()
    this.resposiveSub.unsubscribe()
  }

  private _tween: Tween
  
  private get tweenPlaying(): boolean {
    return (this._checkLoading || this._transitionSub != null)
  }

  private _transitionSub: any
  private _lastSizes: [number, number]
  private startAnimate(prev: number, current: number) {
    let _data = this.getSlideData()

    let tw: Tween = this._tween

    if (this._transitionSub) {
      this._transitionSub.unsubscribe()
      tw.reset()
    }
    const ctx = this._ctx
    let dest, src, currentRect, intersect: Rect
    let s: number
    let lastSizes = [this._canvas.width, this._canvas.height]
    const _layout: Rect = new Rect(0, 0, this._canvas.width, this._canvas.height)
    this._transitionSub = tw.change.subscribe((e: TweenEvent) => {
      switch (e.type) {
        case "change":
          if (lastSizes[0] != this._canvas.width || lastSizes[1] != this._canvas.height) {
            ctx.clearRect(0, 0, lastSizes[0], lastSizes[1])
            lastSizes = [this._canvas.width, this._canvas.height]
            _layout.height = this._canvas.height
            _layout.width = this._canvas.width
            _data = this.getSlideData()
          }
          ctx.clearRect(0, 0, _layout.width, _layout.height)
          for (let sd of [_data.current, _data.previous]) {
            if (!sd.src)
              continue
            currentRect = sd.box.clone
            s = sd.src.naturalWidth / currentRect.width
            currentRect.x = interpolate(e.currentValue, sd.xFrom, sd.xTo)
            intersect = _layout.intersection(currentRect.clone)
            if (!intersect)
              continue
            dest = intersect.clone
            src = intersect.clone
            src.x = intersect.x - currentRect.x
            src.y = intersect.y - currentRect.y
            src.scale(s, s)
            draw(ctx, sd.src, src, dest)
          }
          break;

        case "end":
          this._transitionSub.unsubscribe()
          this._transitionSub = null
          tw.reset()
          break;

        default:
          break;
      }
    })
    tw.start()
  }

  /**
   * [src, dst]
   */
  getDrawData(): [Rect, Rect] {
    const cw: number = this._canvas.width
    const ch: number = this._canvas.height
    const curImg = this._current
    const img = new Point(curImg.naturalWidth, curImg.naturalHeight)
    const scaled = img.clone
    let s: number = cw / scaled.x
    const sy: number = ch / scaled.y
    if (sy < s)
      s = sy
    if (s < 1)
      scaled.scale(s, s)
    const dest: Rect = new Rect(0, 0, scaled.x, scaled.y)
    dest.x = (cw - dest.width) / 2
    dest.y = (ch - dest.height) / 2

    return [new Rect(0, 0, img.x, img.y), dest]
  }

  private getSlideData() {
    const dir: number = this.dir
    const cw: number = this._canvas.width
    const ch: number = this._canvas.height
    const curImg = this._current
    const prevImg = this._previous
    // x: naturalWidth
    // y: naturalHeight
    let imgs: Point[] = [
      null,
      new Point(curImg.naturalWidth, curImg.naturalHeight)
    ]
    if (prevImg)
      imgs[0] = new Point(prevImg.naturalWidth, prevImg.naturalHeight)

    let scaledImgs: Point[] = imgs.map(r => r ? r.clone : null)

    let s: number
    let sy: number
    let i: Point
    for (i of scaledImgs) {
      if (!i)
        continue
      s = cw / i.x
      sy = ch / i.y
      if (sy < s)
        s = sy
      if (s < 1) {
        i.scale(s, s)
      }
    }
    // cur is image to show at index 1
    let cur: Rect = new Rect(0, 0, scaledImgs[1].x, scaledImgs[1].y)
    // prev is current image to hide  at index 0
    let prev: Rect = prevImg ?
      new Rect(0, 0, scaledImgs[0].x, scaledImgs[0].y)
      : null
    // align scaled into layout
    cur.x = (cw - cur.width) / 2
    cur.y = (ch - cur.height) / 2
    if (prev) {
      prev.x = (cw - prev.width) / 2
      prev.y = (ch - prev.height) / 2
    }

    // calculate start and end values
    // cur come from right to left
    type fromTo = [number, number]
    let curTo: fromTo = [-cw * dir + cur.x, cur.x]
    // cur come from right to left
    let prevTo: fromTo = prev ? [prev.x, cw * dir + prev.x] : [NaN, NaN]
    return {
      dir: 1,
      current: {
        src: curImg,
        box: cur,
        xFrom: curTo[0],
        xTo: curTo[1]
      },
      previous: {
        src: prevImg,
        box: prev,
        xFrom: prevTo[0],
        xTo: prevTo[1]
      }
    }
  }

  private dir: 1 | -1 = 1

  private _current: HTMLImageElement
  private _previous: HTMLImageElement
  private _checkLoading: boolean

  private checkLoad(prev: number, current: number) {
    this._checkLoading = true
    let skiPprev: boolean = false
    let resolve = () => {
      if ((this._previous || skiPprev) && this._current) {
        this._checkLoading = false
        this.startAnimate(prev, current)
      }
    }
    this._current = null
    this._previous = null
    if (!isNaN(prev)) {
      if (this.images[prev]) {
        this._previous = this.images[prev]
      }
      else {
        this.getImage(prev).subscribe(img => {
          this._previous = img
          resolve()
        })
      }
    }
    else
      skiPprev = true
    if (!this.images[current]) {
      this.getImage(current).subscribe(img => {
        this.images[current] = img
        this._current = img
        resolve()
      })
    }
    else {
      this._current = this.images[current]
    }
    resolve()
  }

  loading: boolean
  loadingProgress: number

  getImage(index: number): Observable<HTMLImageElement> {
    return Observable.create((o: Observer<HTMLImageElement>) => {
      this.images[index] = new Image()
      let xhr: XHRImage = new XHRImage()
      xhr.load(this.images[index], this.urls[index])
        .subscribe(event => {
          this.loadingProgress = event.loaded / event.total
        },
        err => { }, () => {
          this.loading = false
          o.next(this.images[index])
          o.complete()
        })
    })
  }


  private draw(clear: boolean = true) {
    if (this.tweenPlaying)
      return
    if (!this._current)
      return
    if (clear)
      clearCanvas(this._ctx, this._canvas)
    const data = this.getDrawData()
    draw(this._ctx, this._current, data[0], data[1])
  }

}


interface SlideData {
  dir: number,
  current: {
    src: Point
    box: Rect
    xFrom: number
    xTo: number
  }
  previous: {
    src: Point
    box: Rect
    xFrom: number
    xTo: number
  }
}

