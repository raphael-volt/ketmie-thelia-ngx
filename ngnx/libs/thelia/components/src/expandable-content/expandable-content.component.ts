import { Component, OnInit, Input } from '@angular/core';
import {
  animate,
  state,
  style,
  transition,
  trigger,
} from '@angular/animations';
@Component({
  selector: 'expandable-content',
  templateUrl: './expandable-content.component.html',
  styleUrls: ['./expandable-content.component.css'],
  animations: [
    trigger('expansion', [
      state('collapsed', style({transform: 'translateY(-100%)', visibility: 'hidden'})),//style({height: '0px', visibility: 'hidden'})),
      state('expanded', style({visibility: 'visible', transform: 'translateY(0%)'})),//style({height: '*', visibility: 'visible'})),
      transition('expanded <=> collapsed', animate('1500ms cubic-bezier(0.4,0.0,0.2,1)'))
    ]),
    trigger('expansion2', [
      state('collapsed', style({height: '0px', visibility: 'hidden'})),
      state('expanded', style({height: '*', visibility: 'visible'})),
      transition('expanded <=> collapsed', animate('1500ms cubic-bezier(0.4,0.0,0.2,1)'))
    ]),

  ]
})
export class ExpandableContentComponent implements OnInit {
  constructor() {}

  expandedState: 'expanded' | 'collapsed' = 'collapsed'
  
  ngOnInit() {}
  animated: boolean = false
  expandedEnd: boolean = false
  
  private _expanded: boolean = false
  @Input()
  get expanded(): boolean {
    return this._expanded
  }
  set expanded(value: boolean) {
    if(this._expanded == value)
      return
    this._expanded = value
    this.expandedState = value ? 'expanded':'collapsed'
    this.animated = true  
  }

  expansionDone(event) {
    this.animated = false
    console.log("expansionDone")
  }
}
