import { Directive, ElementRef, OnChanges, SimpleChanges, Input} from '@angular/core';
import { px } from "@thelia/utils";
import { Subscription } from "rxjs";
@Directive({
  selector: '[squareBox]'
})
export class SquareBoxDirective implements OnChanges {

  @Input()
  squareBox

  private taget: HTMLElement

  constructor(
    ref: ElementRef) {
      this.taget = ref.nativeElement
  }

  ngOnChanges(changes: SimpleChanges) {
    if(changes.squareBox) {
      const sl: any = changes.squareBox.currentValue
      if(typeof sl == "number" && ! isNaN(sl)) {
        const s: CSSStyleDeclaration = this.taget.style
        s.height = px(sl)
        s.width = px(sl)
      }
    }
  }

}
