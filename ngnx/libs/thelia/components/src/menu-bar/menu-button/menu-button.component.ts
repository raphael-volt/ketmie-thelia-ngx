import { Component, OnInit, ElementRef, ViewChild } from '@angular/core';
import { MatRipple } from '@angular/material';

@Component({
  selector: '[menu-button]',
  templateUrl: './menu-button.component.html',
  styleUrls: ['../menu-button-base.scss', './menu-button.component.css']
})
export class MenuButtonComponent implements OnInit {
  @ViewChild(MatRipple) ripple: MatRipple;

  target: HTMLElement;
  constructor(ref: ElementRef) {
    this.target = ref.nativeElement;
  }
  ngOnInit() {}
}
