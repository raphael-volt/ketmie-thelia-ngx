import { Directive, OnChanges, SimpleChanges, ElementRef, Input, OnDestroy, AfterViewInit } from '@angular/core';
import { Subscription, Observable, Observer } from "rxjs";
import { map } from "rxjs/operators";
import {
  LayoutService,
  LayoutSizes,
  ResponsiveAliases,
  getImgBoxMaxWidth,
  getImgBoxMaxHeight,
  getMaxScreenWidth
} from "../../layout.service";
import { RequestService } from "../../api/request.service";
import { SubscriptionCollector } from "../../shared/utils/subscription.utils";
import { ProductDetail } from "../../api/api.model";
import { Rect } from "../../shared/math/rect";
import { Point } from "../../shared/math/point";
import { SimpleMatrix } from "../../shared/math/matrix";
import { px, getComputedBounds } from "../../shared/utils/css-utils";
import { draw, clearCanvas } from "../../shared/utils/canvas.util";
import { Tween, TweenEvent, Quadratic, Ease, interpolate } from "../../shared/tween/ease";
const TRANSITIONEND: string = "transitionend"
const NEXT: number = -1
const PREV: number = 1
@Directive({
  selector: '[imgBox]'
})
export class ImgBoxDirective implements OnChanges, OnDestroy, AfterViewInit {

  private _aliasChanged: boolean = false

  private aliasChange = (alias?: ResponsiveAliases) => {
    const sl = Math.ceil(getMaxScreenWidth() * .5)
    if (sl != this._sideLength) {
      this._sideLength = sl
      this._aliasChanged = true
    }
    if (!this._initialized || !this.imgBox)
      return
    // this.layoutChange()
    this.createUrls(this.imgBox.images)
    let i = 0
    if (this._currentIndex > -1)
      i = this._currentIndex
    this._subHelper.add = this.showImageAt(i, NEXT)
      .subscribe(v => { }, null, () => {
        this._subHelper.unsubscribeAll()
      })
  }

  private _transitionDuration: number = 350
  private _transitionEase: string = "ease-in"

  @Input()
  imgBox: ProductDetail

  private _layoutSubHelper: SubscriptionCollector = new SubscriptionCollector()
  private _subHelper: SubscriptionCollector = new SubscriptionCollector()

  private _target: HTMLCanvasElement
  private _ctx: CanvasRenderingContext2D

  private _initialized: boolean = false
  private _propertyChangeFlag: boolean

  private _navEnabled: boolean
  private _urls: string[] = []

  private _sideLength: number = NaN

  private _canvasBounds: LayoutSizes
  private _imgPrev: HTMLImageElement
  private _imgCurrent: HTMLImageElement
  private _transitionFlag: boolean = false

  private _tween: Tween = new Tween(new Ease(0, 1, 350, Quadratic.out))

  constructor(
    ref: ElementRef,
    request: RequestService,
    private layout: LayoutService
  ) {
    this._target = ref.nativeElement
    this._ctx = this._target.getContext("2d")
    this.getImgUrl = id => {
      return request.getDefaultProductImageUrl(id, this._sideLength)
    }
  }

  prev() {
    if (!this._navEnabled)
      return
    let next = this._currentIndex - 1
    if (next < 0)
      next = this._urls.length - 1
    this._subHelper.add = this.showImageAt(next, PREV).subscribe(v => {
      this._subHelper.unsubscribeAll()
    })
  }

  next() {
    if (!this._navEnabled)
      return
    let next = this._currentIndex + 1
    if (next >= this._urls.length)
      next = 0
    this._subHelper.add = this.showImageAt(next, NEXT).subscribe(v => {
      this._subHelper.unsubscribeAll()
    })
  }

  close(): Observable<boolean> {
    this._imgPrev = this._imgCurrent
    this._imgCurrent = null
    return this.startAnimate(NEXT).pipe(
      map(v => {
        this._imgPrev = null
        this._currentIndex = -1
        return v
      })
    )
  }

  ngAfterViewInit() {
    this.aliasChange()
    this.layoutChange()
    this._initialized = true
    this._layoutSubHelper.add = this.layout.responsiveAliasChange.subscribe(this.aliasChange)
    this._layoutSubHelper.add = this.layout.layoutChange.subscribe(this.layoutChange)
    this.aliasChange()
    this.layoutChange()
    if (this._propertyChangeFlag) {
      this._propertyChangeFlag = false
      this.setProduct(this.imgBox)
    }
  }


  ngOnChanges(changes: SimpleChanges) {
    if (changes.imgBox) {
      const change = changes.imgBox
      if (!this._initialized) {
        this._propertyChangeFlag = true
        return
      }
      this.setProduct(change.currentValue)
    }
  }

  ngOnDestroy() {
    this._layoutSubHelper.unsubscribeAll()
    this._subHelper.unsubscribeAll()
  }

  private setProduct(p: ProductDetail) {
    const ids = p ? p.images : undefined
    if (!p || typeof p != "object" || !ids || !ids.length) {
      return
    }
    this._navEnabled = ids.length > 1
    this.createUrls(ids)
    this._subHelper.add = this.showImageAt(0, PREV)
      .subscribe(v => { }, null, () => {
        this._subHelper.unsubscribeAll()
      })
  }

  private createUrls(ids: string[]) {
    this._urls = ids.map(id => this.getImgUrl(id))
  }

  private getImgUrl: (id: string) => string

  private endHandler = (event: TransitionEvent) => {
    const img: HTMLImageElement = event.currentTarget as HTMLImageElement
    img.removeEventListener(TRANSITIONEND, this.endHandler)
    this._subHelper.unsubscribeAll()
  }

  private _currentIndex: number = -1

  private showImageAt(index: number, dir: number): Observable<boolean> {
    return Observable.create((observer: Observer<boolean>) => {
      this._currentIndex = index
      this._transitionFlag = true
      const ib = this.imgBox
      if (!ib || !ib.images || !ib.images.length) {
        observer.next(false)
        return observer.complete()
      }
      const img = new Image()
      const loaded = () => {
        img.removeEventListener('load', loaded)
        this._imgPrev = this._imgCurrent
        this._imgCurrent = img
        const done = () => {
          this._subHelper.unsubscribeAll()
          this._transitionFlag = false
          observer.next(true)
          observer.complete()
        }
        this._subHelper.add = this.startAnimate(dir).subscribe(done)
      }
      img.addEventListener('load', loaded)
      img.src = this._urls[index]
    })
  }
  private startAnimate(dir): Observable<boolean> {
    return Observable.create((observer: Observer<boolean>) => {
      this.dir = dir
      this.updateCanvasLayout(this.calculateCanvasLayout())

      let _lastSizes: [number, number]
      let _data = this.getSlideData()

      let tw: Tween = this._tween
      const ctx = this._ctx
      let dest: Rect
      let src: Rect
      let currentRect: Rect
      let intersect: Rect
      let s: number
      let lastSizes = this._canvasBounds.slice()
      const _layout: Rect = new Rect(0, 0, lastSizes[0], lastSizes[1])
      const c = this._target
      let sub = tw.change.subscribe(
        (e: TweenEvent) => {
          if (lastSizes[0] != c.width || lastSizes[1] != c.height) {
            ctx.clearRect(0, 0, lastSizes[0], lastSizes[1])
            lastSizes = [c.width, c.height]
            _layout.height = c.height
            _layout.width = c.width
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
          switch (e.type) {
            case "change":

              break;

            case "end":
              sub.unsubscribe()
              this._transitionFlag = false
              observer.next(true)
              observer.complete()
              break;

            default:
              break;
          }
        })
      tw.start()
    })
  }

  private validateCurrentImgPosition() {

    if (!this._imgCurrent) {
      this.updateCanvasLayout(this.calculateCanvasLayout())
    }
    if (this._imgCurrent && !this._transitionFlag) {
      this._layoutChanded = true
      window.requestAnimationFrame(this.drawCurrentImage)
    }
  }
  private drawCurrentImage = () => {
    const c = this._ctx
    const t = this._target
    clearCanvas(c, t)
    if (this._layoutChanded) {
      this._layoutChanded = false
      this.updateCanvasLayout(this.calculateCanvasLayout())
    }

    const data = this.getDrawData()
    draw(c, this._imgCurrent, data[0], data[1])
  }
  private calculateCanvasLayout(): Rect {
    const parentRect = Rect.fromBouds(this._target.parentElement.getBoundingClientRect())
    return parentRect.letterBox(new Rect(0, 0, this._sideLength, this._sideLength), 1)
  }

  private updateCanvasLayout(rect: Rect) {
    const t = this._target
    const s = t.style
    t.width = rect.width
    t.height = rect.height
    s.top = px(rect.y)
    s.left = px(rect.x)
    this._canvasBounds = [rect.width, rect.height]
  }
  private _layoutChanded: boolean = false
  private layoutChange = (layout?: LayoutSizes) => {

    if (!this._initialized) return
    if (this._aliasChanged) {
      this._aliasChanged = false
    }
    if (!this._transitionFlag) {
      this._layoutChanded = true
      this.validateCurrentImgPosition()
    }
  }

  private getSlideData() {
    const dir: number = this.dir
    const cw: number = this._canvasBounds[0]
    const ch: number = this._canvasBounds[1]
    const curImg = this._imgCurrent
    const prevImg = this._imgPrev
    const gap = cw * .1
    let imgs: Point[] = [
      null,
      null
    ]
    if (curImg)
      imgs[1] = new Point(curImg.naturalWidth, curImg.naturalHeight)
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
    let cur: Rect = curImg ? new Rect(0, 0, scaledImgs[1].x, scaledImgs[1].y)
      : null
    // prev is current image to hide  at index 0
    let prev: Rect = prevImg ?
      new Rect(0, 0, scaledImgs[0].x, scaledImgs[0].y)
      : null
    // align scaled into layout
    if (cur) {
      cur.x = (cw - cur.width) / 2
      cur.y = (ch - cur.height) / 2
    }
    if (prev) {
      prev.x = (cw - prev.width) / 2
      prev.y = (ch - prev.height) / 2
    }

    // calculate start and end values
    // cur come from right to left
    type fromTo = [number, number]
    let curTo: fromTo = cur ? [-(cw + gap) * dir + cur.x, cur.x] : [NaN, NaN]
    // cur come from right to left
    let prevTo: fromTo = prev ? [prev.x, (cw + gap) * dir + prev.x] : [NaN, NaN]
    return {
      dir: dir,
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


  /**
   * [src, dst]
   */
  getDrawData(): [Rect, Rect] {
    const cw: number = this._canvasBounds[0]
    const ch: number = this._canvasBounds[1]
    const curImg = this._imgCurrent
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


}
