import { Directive, ElementRef, Input, OnChanges, SimpleChanges } from '@angular/core';

@Directive({
  selector: '[innerHTML]'
})
export class InnerHtmlDirective implements OnChanges {
  @Input() innerHTML: string;

  private target: HTMLElement;

  constructor(ref: ElementRef) {
    this.target = ref.nativeElement;
  }

  ngOnChanges(changes: SimpleChanges) {
    if (changes.innerHTML) this.target.innerHTML = changes.innerHTML.currentValue;
  }
}
