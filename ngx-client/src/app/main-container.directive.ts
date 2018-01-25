import { Directive, ElementRef, AfterViewInit } from '@angular/core';
import { LayoutService, LayoutSizes } from "./layout.service";
import { px } from "./shared/utils/css-utils";
@Directive({
  selector: '[mainContainer]'
})
export class MainContainerDirective implements AfterViewInit{

  private target: HTMLElement
  constructor(
    ref: ElementRef,
    private layoutService: LayoutService) { 
      this.target = ref.nativeElement
  }

  ngAfterViewInit() {
    this.updateSize(this.layoutService.layout)
    this.layoutService.layoutChange.subscribe(this.updateSize)
  }

  private updateSize = (layout: LayoutSizes) => {
    const s: CSSStyleDeclaration = this.target.style
    s.width = px(layout[0])
    s.height = px(layout[1])
  }

}
