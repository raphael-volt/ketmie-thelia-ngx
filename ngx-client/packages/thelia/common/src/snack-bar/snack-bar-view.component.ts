import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'snack-bar-view',
  templateUrl: './snack-bar-view.component.html',
  styleUrls: ['./snack-bar-view.component.css']
})
export class SnackBarViewComponent {

  message: string
  action: string
  
  constructor() { }


  ngOnInit() {
  }

}
