import {
  Directive,
  ElementRef,
  Input,
  Output,
  OnChanges,
  SimpleChanges,
  SimpleChange,
  EventEmitter
} from '@angular/core';

export type SliderState = 'none' | 'in' | 'out';
export type SliderEvent = 'start' | 'end';
export type SliderDirection = 'left' | 'right' | 'top' | 'bottom';

const sliderStates: SliderState[] = ['in', 'none', 'out'];
const sliderDirections: SliderDirection[] = ['bottom', 'left', 'right', 'top'];

const DEFAULT_DURATION: number = 1000;

const addClass = (cl: DOMTokenList, cls: string) => {
  if (!cl.contains(cls)) {
    cl.add(cls);
  }
};
const removeClass = (cl: DOMTokenList, cls: string) => {
  if (cl.contains(cls)) {
    cl.remove(cls);
  }
};
const sliderClass = (dir: SliderDirection): string => {
  return 'slider-' + dir;
};
const isSliderState = (value: any): value is SliderState => {
  return sliderStates.indexOf(value) > -1;
};
const isSliderDirection = (value: any): value is SliderDirection => {
  return sliderDirections.indexOf(value) > -1;
};

@Directive({
  selector: '[sliderDirection]'
})
export class SliderDirective implements OnChanges {
  private style: CSSStyleDeclaration;
  private target: HTMLElement;

  @Output() events: EventEmitter<SliderEvent> = new EventEmitter<SliderEvent>();

  @Input() sliderState: SliderState;
  private _sliderState: SliderState = 'none';

  @Input() sliderDirection: SliderDirection;
  private _sliderDirection: SliderDirection;

  constructor(ref: ElementRef) {
    this.target = ref.nativeElement;
    this.style = this.target.style;
    const cl = this.target.classList;
    addClass(cl, 'slider');
    this.target.addEventListener('transitionend', () => this.events.emit('end'), false);
  }

  ngOnChanges(changes: SimpleChanges) {
    let change: SimpleChange = changes.sliderDirection;
    const cl = this.target.classList;
    if (change && isSliderDirection(change.currentValue)) {
      if (isSliderDirection(change.previousValue)) removeClass(cl, sliderClass(change.previousValue));
      addClass(cl, sliderClass(change.currentValue));
    }
    change = changes.sliderState;
    if (change) {
      if (!isSliderState(change.currentValue)) this._sliderState = 'none';
      else this._sliderState = change.currentValue;
      this.validateChanges();
    }
  }

  private validateChanges() {
    const cl = this.target.classList;
    switch (this._sliderState) {
      case 'in':
      case 'out':
        this.events.emit('start');
        break;
    }
    switch (this._sliderState) {
      case 'none':
        removeClass(cl, 'in');
        removeClass(cl, 'out');
        break;

      case 'in':
        removeClass(cl, 'out');
        addClass(cl, 'in');
        break;

      case 'out':
        removeClass(cl, 'in');
        addClass(cl, 'out');
        break;

      default:
        break;
    }
  }
}
