import { Directive, Input, Output, EventEmitter, ElementRef, OnDestroy, OnChanges, SimpleChanges } from '@angular/core';
import { RequestService } from '@ngnx/thelia/api';
import { XHRImage, XHRImageEvent } from '@ngnx/thelia/utils';
import { Observable, Observer, Subscription } from 'rxjs';
@Directive({
  selector: '[apiImage]'
})
export class ApiImageDirective implements OnChanges, OnDestroy {
  @Input() productId: string;
  @Input() imgWidth: string;
  @Input() imgHeight: string;
  @Input() imgScale: number;
  @Output() loaderEvent: EventEmitter<XHRImageEvent> = new EventEmitter<XHRImageEvent>();

  private img: HTMLImageElement;

  constructor(ref: ElementRef, private request: RequestService) {
    this.img = ref.nativeElement;
  }

  private id: string;
  private width: string;
  private height: string;
  private scale: number = 1;

  ngOnChanges(changes: SimpleChanges) {
    if (changes.productId) {
      this.id = changes.productId.currentValue;
    }
    if (changes.imgWidth) {
      this.width = changes.imgWidth.currentValue;
    }
    if (changes.imgHeight) {
      this.height = changes.imgHeight.currentValue;
    }
    if (this.id && (this.imgHeight || this.imgWidth)) {
      const src: string = this.request.getProductImageUrl(this.id, Number(this.width), Number(this.height));
      const ldr: XHRImage = new XHRImage();
      const sub: Subscription = ldr.load(this.img, src).subscribe(
        (event: XHRImageEvent) => {
          this.loaderEvent.emit(event);
        },
        err => {},
        () => {
          sub.unsubscribe();
        }
      );
    }
    if (changes.imgScale) {
      let s: number = changes.imgScale.currentValue;
      if (typeof s == 'number' && !isNaN(s)) {
        const style = this.img.style;
        if (s == 1) {
          style.transform = 'none';
        } else {
          style.transform = `scale(${s},${s})`;
        }
      }
    }
  }
  ngOnDestroy() {
    // console.log('ApiImageDirective.ngOnDestroy')
  }
}
