import { Component, OnInit, ViewChild, ElementRef, AfterViewInit } from '@angular/core';
import { Rect } from '@ngnx/rzl/math';
import { TweenBase, TweenStatus, TweenChangeEvent, TweenData, Quadratic } from '@ngnx/rzl/tween';

@Component({
  selector: 'tween-example',
  templateUrl: './tween-example.component.html',
  styleUrls: ['./tween-example.component.css']
})
export class TweenExampleComponent implements OnInit {
  @ViewChild('img') img: ElementRef | undefined;

  constructor() {}

  startLabel: string = 'PLAY';
  toggleLabel: string = 'IN';
  statusLabel: TweenStatus;

  private _selectedOption: any;

  get selectedOption(): any {
    return this._selectedOption;
  }
  set selectedOption(value: any) {
    if (this._selectedOption == value) return;
    this._selectedOption = value;
    console.log('selected option changed', value);
  }
  selectOptions = [
    { name: 'Option A', value: 'a' },
    { name: 'Option B', value: 'b' },
    { name: 'Option C', value: 'c' },
    { name: 'Option D', value: 'd' },
    { name: 'Option E', value: 'e' },
    { name: 'Option F', value: 'f' },
    { name: 'Option G', value: 'g' },
    { name: 'Option H', value: 'h' }
  ];
  tween: TweenBase;
  ngOnInit() {
    let t: TweenBase = new TweenBase({
      duration: 2000,
      start: 0,
      end: 100,
      ease: Quadratic.inOut
    });
    this.tween = t;
    this.statusLabel = t.status;
  }

  ngAfterViewInit() {
    let i: HTMLElement = this.img.nativeElement;
    const f = (v: number) => {
      i.style.transform = `translateX(${v}%)`;
    };
    const t = this.tween;
    t.events.subscribe((e: TweenChangeEvent) => {
      f(e.value);
    });
    t.statusChange.subscribe(s => {
      this.statusLabel = s;
      switch (s) {
        case 'pause':
          this.startLabel = 'PLAY';

          break;
        case 'in':
          this.toggleLabel = 'OUT';
          this.startLabel = 'PAUSE';
          //t.data.ease = Quadratic.in
          break;

        case 'out':
          this.toggleLabel = 'IN';
          this.startLabel = 'PAUSE';
          //t.data.ease = Quadratic.out
          break;

        case 'begin':
          this.startLabel = 'PLAY';
          this.toggleLabel = 'IN';

          break;

        case 'end':
          this.startLabel = 'PLAY';
          this.toggleLabel = 'OUT';

          break;

        default:
          break;
      }
    });
  }
  start() {
    const t = this.tween;
    if (t.status == 'begin' || t.status == 'end') {
      t.start();
    } else {
      if (this.tween.running) {
        t.pause();
      } else {
        t.resume();
      }
    }
  }
  toggle() {
    this.tween.toggle();
  }
  resume() {
    this.tween.resume();
  }
  reverse() {
    this.tween.reverse();
  }
}
