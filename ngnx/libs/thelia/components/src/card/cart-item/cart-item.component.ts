import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { DELETE } from '@ngnx/thelia/common';
@Component({
  selector: 'cart-item',
  templateUrl: './cart-item.component.html',
  styleUrls: ['./cart-item.component.css']
})
export class CartItemComponent implements OnInit {
  constructor() {}
  @Output() declinationChange: EventEmitter<any> = new EventEmitter();
  @Output() quantityChange: EventEmitter<any> = new EventEmitter();
  @Output() delete: EventEmitter<any> = new EventEmitter();

  @Input() cardItem;
  ngOnInit() {}

  deleteLabel: string = DELETE;
}
