import { Directive, Input, OnChanges, SimpleChanges, ElementRef, HostBinding } from '@angular/core';

@Directive({
  selector: '[tpl-dir]'
})
export class TemplateDirective implements OnChanges {

  @HostBinding('class.k-dark') isDark: boolean = false;
  @HostBinding('class.k-light') isLight: boolean = false;

  @HostBinding('class.slider') slider: boolean = false;
  @HostBinding('class.in') slideIn: boolean = false;
  @HostBinding('class.out') slideOut: boolean = false;
  
  @HostBinding('class.left') slideL: boolean = false;
  @HostBinding('class.right') slideR: boolean = false;
  @HostBinding('class.top') slideT: boolean = false;
  @HostBinding('class.bottom') slideB: boolean = false;

  @Input()
  kTheme: "light" | "dark" = "light"

  @Input()
  kSliderDir: "left" | "right" | "top" | "bottom" = "left"

  @Input()
  kSlider: boolean = false

  @Input()
  kSlideIn: boolean = false

  @Input()
  kSlideOut: boolean = false

  private element: HTMLElement
  constructor(ref: ElementRef) {
    this.element = ref.nativeElement
    console.log('TemplateDirective.constructor')
  }

  ngOnChanges(v: SimpleChanges) {
    if (v.kTheme) {
      const t = v.kTheme.currentValue
      this.isDark = (t == "dark")
      this.isLight = (t == "light")
    }
    if (v.kSlider) {
      this.slider = v.kSlider.currentValue === true
    }
    if (v.kSlideIn) {
      this.slideIn = v.kSlideIn.currentValue === true
    }
    if (v.kSlideOut) {
      this.slideOut = v.kSlideIn.currentValue === true
    }
    if (v.kSliderDir) {
      const d = v.kSliderDir.currentValue 
      let dir = null
      let vals = ["left", "right", "top", "bottom"]
      let props = ["slideL", "slideR", "slideT", "slideB"]
      for(let i in vals) {
        this[props[i]] = vals[i] == d
      }
    }
  }

}
