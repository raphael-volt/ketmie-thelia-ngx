import { Component, OnInit, ElementRef } from '@angular/core';

@Component({
  selector: 'menu-button',
  templateUrl: './menu-button.component.html',
  styleUrls: ['./menu-button.component.css']
})
export class MenuButtonComponent implements OnInit {
  constructor(ref: ElementRef) {
    this.host = ref.nativeElement
  }

  host: HTMLElement
  ngOnInit() {}
}
