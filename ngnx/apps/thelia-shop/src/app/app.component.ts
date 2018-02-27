import { Component, OnInit, ViewChild, ElementRef, AfterViewInit } from '@angular/core';
import { Rect } from '@ngnx/rzl/math';
import { TweenBase, TweenStatus, TweenChangeEvent, TweenData, Quadratic } from '@ngnx/rzl/tween';
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit, AfterViewInit {
  constructor() { }

  startLabel: string = "PLAY"
  toggleLabel: string = "IN"
  statusLabel: TweenStatus

  @ViewChild('img') img: ElementRef | undefined;

  tween: TweenBase;
  ngOnInit() {
    let t: TweenBase = new TweenBase({
      duration: 2000,
      start: 0,
      end: 100,
      ease: Quadratic.inOut
    });
    this.tween = t
    this.statusLabel = t.status
  }

  ngAfterViewInit() {
    let i: HTMLElement = this.img.nativeElement;
    const f = (v: number) => {
      i.style.transform = `translateX(${v}%)`;
    };
    const t = this.tween
    t.events.subscribe((e: TweenChangeEvent) => {
      f(e.value)
    })
    t.statusChange.subscribe(s => {
      this.statusLabel = s
      switch (s) {
        case "pause":
          this.startLabel = "PLAY"

          break;
        case "in":
          this.toggleLabel = 'OUT'
          this.startLabel = "PAUSE"
          //t.data.ease = Quadratic.in
          break;

        case "out":
          this.toggleLabel = 'IN'
          this.startLabel = "PAUSE"
          //t.data.ease = Quadratic.out
          break;

        case "begin":
          this.startLabel = "PLAY"
          this.toggleLabel = 'IN'

          break;

        case "end":
          this.startLabel = "PLAY"
          this.toggleLabel = 'OUT'

          break;

        default:
          break;
      }
    })
  }
  start() {
    const t = this.tween
    if (t.status == "begin" || t.status == "end") {
      t.start()
    }
    else {
      if (this.tween.running) {
        t.pause()
      }
      else {
        t.resume()
      }

    }
  }
  toggle() {
    this.tween.toggle()
  }
  resume() {
    this.tween.resume()
  }
  reverse() {
    this.tween.reverse()
  }
}
