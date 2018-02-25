import { Component, OnInit } from '@angular/core';
import { ModelService } from "../model.service";
@Component({
  selector: 'api-model',
  templateUrl: './model.component.html',
  styleUrls: ['./model.component.css']
})
export class ModelComponent implements OnInit {

  constructor(private srv: ModelService) { }

  result
  ngOnInit() {
    this.result = this.srv.get()
  }

}
