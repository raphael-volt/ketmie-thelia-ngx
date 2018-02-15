import { Component, AfterViewChecked, EventEmitter, Output, ElementRef, ViewChild } from '@angular/core';

@Component({
  selector: '[menu-bar]',
  templateUrl: './menu-bar.component.html',
  styleUrls: ['./menu-bar.component.css'],
  host: {
    '(click)':'barClickHandler($event)'
  }
})
export class MenuBarComponent implements AfterViewChecked {

  private target: HTMLElement
  constructor(ref: ElementRef) {
    this.target = ref.nativeElement
   }

  @Output()
  barClick: EventEmitter<void> = new EventEmitter()

  @Output()
  home: EventEmitter<boolean> = new EventEmitter<boolean>()
  
  @Output()
  menu: EventEmitter<boolean> = new EventEmitter<boolean>()
  
  @Output()
  customer: EventEmitter<boolean> = new EventEmitter<boolean>()
  
  @Output()
  card: EventEmitter<boolean> = new EventEmitter<boolean>()
  
  @ViewChild("menuCtn")
  menuCtn: ElementRef | undefined
  
  @ViewChild("menuIcon")
  menuIcon: ElementRef | undefined

  ngAfterViewChecked() {
    const icon: HTMLElement = this.menuIcon.nativeElement
    icon.style.transform = "none"
    const bounds = icon.getBoundingClientRect()// ${bounds.height}px, ${0}px
    icon.style.transform = `rotate(270deg) translate(-${bounds.width}px,${0}px)`
    icon.style.transformOrigin = "0% 0%"
    
    const ctn: HTMLElement = this.menuCtn.nativeElement
    ctn.style.width = `${bounds.height}px`
    ctn.style.height = `${bounds.width}px`
  }

  barClickHandler(event: MouseEvent) {
    if(event.target == this.target)
      this.barClick.emit()
  }
}
