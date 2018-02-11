import { Component, OnInit } from '@angular/core';
import { SliderBaseComponent } from "../slider-base.component";
@Component({
  selector: 'theme',
  templateUrl: './theme.component.html',
  styleUrls: ['./theme.component.css']
})
export class ThemeComponent extends SliderBaseComponent implements OnInit {

  constructor() { 
    super()
  }

  ngOnInit() {
  }

}
