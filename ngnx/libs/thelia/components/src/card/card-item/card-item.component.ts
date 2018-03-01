import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { DELETE } from '@ngnx/thelia/common';
@Component({
  selector: 'card-item',
  templateUrl: './card-item.component.html',
  styleUrls: ['./card-item.component.css']
})
export class CardItemComponent implements OnInit {
  constructor() {}
  @Output() declinationChange: EventEmitter<any> = new EventEmitter();
  @Output() quantityChange: EventEmitter<any> = new EventEmitter();
  @Output() delete: EventEmitter<any> = new EventEmitter();

  @Input() cardItem;
  ngOnInit() {}

  deleteLabel: string = DELETE;
}
