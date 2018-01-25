import { Injectable, EventEmitter } from '@angular/core';
export type LayoutSizes = [number, number]
@Injectable()
export class LayoutService {

  layout: LayoutSizes = [0, 0]
  layoutChange: EventEmitter<LayoutSizes> = new EventEmitter<LayoutSizes>()

  constructor() { 
    this.setLayoutSizes()
    window.addEventListener('resize', this.setLayoutSizes)
  }

  private setLayoutSizes = (event?: Event) => {
    window
    this.layout[0] = window.innerWidth
    this.layout[1] = window.innerHeight
    this.layoutChange.emit(this.layout)
    // window.requestAnimationFrame(()=>{ })
  }

}
