import { Component, OnInit, Input, ElementRef } from '@angular/core';
import { MenuButtonComponent } from "../menu-button/menu-button.component";
import { MatRipple } from "@angular/material";
@Component({
  selector: '[icon-button]',
  templateUrl: './icon-button.component.html',
  styleUrls: ['../menu-button-base.scss', './icon-button.component.css']
})
export class IconButtonComponent extends MenuButtonComponent {
  constructor(ref: ElementRef) {
    super(ref)
  }

  iconClass: string[] = [];

  private _kiIcon: string;

  @Input() counter: number = 0;

  @Input()
  get kiIcon(): string {
    return this._kiIcon;
  }
  set kiIcon(value: string) {
    if (this._kiIcon == value) return;
    this._kiIcon = value;
    if (value) this.iconClass[0] = value;
    else if (this.iconClass.length) this.iconClass.length = 0;
  }
  ngOnInit() {}
}
