import { Component, OnInit, OnChanges, SimpleChanges, Input, EventEmitter, Output } from '@angular/core';
import { IDeclinationMap, IDeclination, IDeclinationItem, CardItem } from "../api.model";
import { DeclinationService } from "../declination.service";
@Component({
  selector: 'declination-select',
  templateUrl: './declination-select.component.html',
  styleUrls: ['./declination-select.component.css']
})
export class DeclinationSelectComponent implements OnInit, OnChanges {

  @Output()
  itemIdChange:EventEmitter<string> = new EventEmitter<string>()
  
  constructor(private decli: DeclinationService) { }

  declinationChange(id) {
    this.itemIdChange.emit(id)
  }

  declinations: IDeclinationItem[] = []

  @Input()
  controlType: "select" | "checkbox" = "checkbox"
  
  @Input()
  formControlName = ""
  
  @Input()
  declinationId: string
  
  @Input()
  itemId: string

  ngOnInit() {
    console.log('DeclinationSelectComponent.ngOnInit')
  }
  ngOnChanges(changes: SimpleChanges) {
    if(changes.declinationId) {
      const d = this.decli.get(changes.declinationId.currentValue)
      this.declinations = d ? this.decli.sortBySize(d):[]
    }
  }

}
