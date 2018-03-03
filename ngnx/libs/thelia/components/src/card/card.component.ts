import { Component, Directive, OnInit, AfterViewInit } from '@angular/core';
import { SliderBaseComponent } from '../slider-base.component';
import { DeclinationService, CardService } from '@ngnx/thelia/api';
import { serializableCardItem, Card, CardItem, IDeclination, IDeclinationItem } from '@ngnx/thelia/model';

@Component({
  selector: 'card',
  templateUrl: './card.component.html',
  styleUrls: ['./card.component.scss']
})
export class CardComponent extends SliderBaseComponent implements OnInit, AfterViewInit {
  constructor(private decli: DeclinationService, public service: CardService) {
    super();
  }

  card: Card;

  ngOnInit() {
    this.card = this.service.card;
  }

  ngAfterViewInit() {
    setTimeout(() => {
      this.slideIn();
    }, 1);
  }

  quantityChange(item: CardItem, value: number) {
    value = Number(value);
    let p = serializableCardItem(item);
    p.quantity = value;
    this.service.update(p, result => {
      item.quantity = value;
    });
  }

  declinationChange(item: CardItem, value: IDeclinationItem) {
    let p = serializableCardItem(item);
    p.decliId = value.id;
    this.service.update(p, result => {
      item.decliId = p.decliId;
    });
  }

  deleteItem(item: CardItem) {
    const i = item.index;
    this.service.remove(item, next => {
      // nothing to do
    });
  }
}

@Directive({
  selector: 'action-row',
  host: {'class': 'action-row'}
})
export class ActionRow {}
