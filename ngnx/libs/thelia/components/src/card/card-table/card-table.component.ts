import { Component, Input, Output, EventEmitter } from '@angular/core';
import { ICard, CardItem } from '@ngnx/thelia/model';
@Component({
  selector: 'table[card-table]',
  templateUrl: './card-table.component.html',
  styleUrls: ['./card-table.component.css']
})
export class CardTableComponent {
  @Input()
  card: ICard = {
    total: 0,
    items: []
  };
  @Output() declinationChange: EventEmitter<any> = new EventEmitter();
  @Output() quantityChange: EventEmitter<any> = new EventEmitter();
  @Output() delete: EventEmitter<any> = new EventEmitter();

  constructor() {}
}
