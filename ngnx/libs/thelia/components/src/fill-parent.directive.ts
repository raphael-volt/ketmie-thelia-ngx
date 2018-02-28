import { Directive, ElementRef, OnDestroy, OnInit, AfterViewInit } from '@angular/core';
import { LayoutService, LayoutSizes } from '@ngnx/thelia/common';
import { Subscription } from 'rxjs';
@Directive({
  selector: '[fillParent]'
})
export class FillParentDirective implements OnDestroy, OnInit, AfterViewInit {
  private target: HTMLElement;
  private layoutSub: Subscription;
  constructor(ref: ElementRef, layout: LayoutService) {
    this.target = ref.nativeElement;
    this.layoutSub = layout.layoutChange.subscribe(this.updateDisplay);
    this.updateDisplay();
  }

  private _updateDisplay = (layout?: LayoutSizes) => {
    const target = this.target;
    const parent = target.parentElement;
    if (!parent) return;
    let ph = parent.offsetHeight;
    const style = window.getComputedStyle(parent); // parent
    const pb = style.paddingBottom.replace('px', '');
    ph -= Number(pb);

    let ty: number = target.offsetTop;
    const h: number = ph - ty;
    target.style.height = Math.floor(h) + 'px';
  };
  private updateDisplay = (layout?: LayoutSizes) => {
    const target = this.target;
    const parent = target.parentElement;
    if (!parent) return;
    const pBounds = parent.getBoundingClientRect();
    const tBounds = target.getBoundingClientRect();
    const style = window.getComputedStyle(parent); // parent
    const pb = Number(style.paddingBottom.replace('px', ''));
    const h: number = pBounds.height - pb - tBounds.top;
    target.style.height = h + 'px';
  };
  ngOnInit() {
    this.updateDisplay();
  }

  ngAfterViewInit() {
    this.updateDisplay();
  }

  ngOnDestroy() {
    this.layoutSub.unsubscribe();
  }
}
