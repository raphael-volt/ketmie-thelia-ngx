import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { Address } from '@ngnx/thelia/model';
import { CHANGE, DELETE } from '@ngnx/thelia/common';
/* host: {
    'class': 'mat-card-actions',
    '[class.mat-card-actions-align-end]': 'align === "end"',
  } */
@Component({
  selector: '[address-view]',
  templateUrl: './address-view.component.html',
  styleUrls: ['./address-view.component.css']
})
export class AddressViewComponent {
  @Output() delete: EventEmitter<any> = new EventEmitter();
  @Output() edit: EventEmitter<any> = new EventEmitter();

  @Input() address: Address = {};

  changeLabel: string = CHANGE;
  deleteLabel: string = DELETE;

  constructor() {}

  ngOnInit() {}
}
