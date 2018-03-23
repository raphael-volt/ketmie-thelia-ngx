import { Component, Directive, OnInit, AfterViewInit } from '@angular/core';
import { SliderBaseComponent } from '../slider-base.component';
import { DeclinationService, CardService } from '@ngnx/thelia/api';
import { serializableCardItem, Card, CardItem, IDeclination, IDeclinationItem } from '@ngnx/thelia/model';
import { NEXT, PREV, ORDER, GIVE_UP } from '@ngnx/thelia/common';
import { trigger, state, style, transition, animate, keyframes } from '@angular/animations';

type SliderState = 'show' | 'hide';
type OrderState = 'card' | 'validate' | 'billingAddress' | 'deliveryAddress' | 'transporter' | 'payement' | 'done';
/*
@Component({
  selector: 'card',
  templateUrl: './card.component.html',
  styleUrls: ['./card.component.scss'],
  animations: [
    trigger('cardSlide', [
      state(
        'show, void',
        style({
          transform: 'translateX(0%)'
        })
      ),
      state(
        'hide',
        style({
          transform: 'translateX(-100%)'
        })
      ),
      transition('show <=> hide', animate('350ms ease-in'))
    ])
  ]
})
*/

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
  hasStepper: boolean = false
  hasCard: boolean = true
  topActionLabel: string = ORDER;
  topActionColor: 'primary' | 'warn' = 'primary';
  cardState: SliderState = 'show';

  orderState: 'card' | 'validate' | 'billingAddress' | 'deliveryAddress' | 'transporter' | 'payement' | 'done' = 'card';

  setOrderState(value: OrderState) {
    this.orderState = value;
    this.hasCard = (value == 'validate' || value == 'card')
    if (value == 'card')
      this.topActionClick()
  }
  topActionClick() {
    if (this.orderState == 'card') {
      this.orderState = 'validate';
      this.topActionColor = 'warn';
      this.topActionLabel = GIVE_UP;
      this.hasStepper = true
    } else {
      this.hasStepper = false
      this.hasCard = true
      this.orderState = 'card';
      this.topActionColor = 'primary';
      this.topActionLabel = ORDER;
    }
    /*
    if (this.orderState == "card") {
      this.orderState = "validate"
      this.topActionColor = "warn"
      this.topActionLabel = GIVE_UP
    }
    else {
      this.orderState = "card"
      this.topActionColor = "primary"
      this.topActionLabel = ORDER
    }
    */
  }
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
