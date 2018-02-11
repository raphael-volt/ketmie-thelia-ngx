import { Component, ElementRef, OnInit, Input, Output, EventEmitter, OnChanges, AfterViewInit, OnDestroy, SimpleChanges } from '@angular/core';
import { LayoutService, LayoutSizes } from "../../layout.service";
import { Subscription } from "rxjs";
@Component({
  selector: 'im-row',
  templateUrl: './im-row.component.html',
  styleUrls: ['./im-row.component.css']
})
export class ImRowComponent implements OnInit, OnChanges, OnDestroy, AfterViewInit {

  @Input()
  prevId: string
  @Input()
  nextId: string
  @Input()
  sideLength: number = NaN
  @Input()
  categoryId: string
  @Input()
  percentPadding: number

  @Output() 
  navClick: EventEmitter<string> = new EventEmitter<string>()
  
  rowHeight: number
  imgScale: number = 1

  private layoutSub: Subscription

  private target: HTMLElement
  constructor(
    ref: ElementRef,
    layout: LayoutService
  ) {
    this.target = ref.nativeElement
    this.layoutSub = layout.layoutChange.subscribe(this.updatedisplay)
  }

  ngOnInit() {
  }

  ngAfterViewInit() {
    this.updatedisplay()
  }

  ngOnChanges(chnages: SimpleChanges) {
    if (chnages.sideLength) {
    }
    this.updatedisplay()
  }

  ngOnDestroy() {
    this.layoutSub.unsubscribe()
  }

  private updatedisplay = (layout?: LayoutSizes) => {
    const sl: number = this.sideLength
    const w = this.target.getBoundingClientRect().width
    let pr: number = this.percentPadding
    if (isNaN(pr))
      pr = 10

    const rp: number = pr / 100
    const rpM: number = 1 + rp
    const bsl = sl * rpM
    if (isNaN(sl) || w < 1) {
      this.imgScale = 1
      this.rowHeight = bsl
      return
    }
    const g: number = rp * sl
    const aw = w - g 
    const maxW = bsl * 2

    let s = aw / maxW
    if (s > 1)
      s = 1
    this.imgScale = s
    this.rowHeight = s * bsl
  }
  private __updatedisplay = (layout?: LayoutSizes) => {
    const sl: number = this.sideLength
    const w = this.target.getBoundingClientRect().width
    const bsl = sl * 1.1
    if (isNaN(sl) || w < 1) {
      this.imgScale = 1
      this.rowHeight = bsl
      return
    }
    const rh = bsl
    const maxW = rh * 2
    let s = w / maxW
    if (s > 1)
      s = 1
    this.imgScale = s
    this.rowHeight = s * bsl
  }

}
