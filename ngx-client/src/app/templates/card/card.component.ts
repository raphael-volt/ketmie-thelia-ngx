import { Component, OnInit } from '@angular/core';
import { SliderBaseComponent } from "../slider-base.component";
import { CardService } from "../../api/card.service";
import { Card } from "../../api/api.model";
@Component({
  selector: 'card',
  templateUrl: './card.component.html',
  styleUrls: ['./card.component.css']
})
export class CardComponent extends SliderBaseComponent implements OnInit {

  constructor(
    public service: CardService) { 
    super()
  }

  card: Card
  
  ngOnInit() {
    this.card = this.service.card
    this.slideIn()
  }

}
