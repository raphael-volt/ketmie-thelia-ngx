import { Component, OnInit,
  Input, OnChanges, SimpleChanges } from '@angular/core';
import { ApiService, ImgTypes } from "../../api/api.service";
@Component({
  selector: 'product-image-box',
  templateUrl: './product-image-box.component.html',
  styleUrls: ['./product-image-box.component.css']
})
export class ProductImageBoxComponent implements OnInit, OnChanges {

  constructor() { }

  @Input()
  imageIds: string[]
  

  ngOnInit() {
  }

  ngOnChanges(changes: SimpleChanges) {

  }

}
