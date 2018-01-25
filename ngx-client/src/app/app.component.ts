import { Component, OnInit } from '@angular/core';
import { ApiService } from "./api/api.service";
import { ShopTree, CMSContent, Category } from "./api/api.model";
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit{
  
  constructor(private apiService: ApiService) { }
  categories: Category[]=[]
  cmsContents: CMSContent[]=[]
  
  ngOnInit() {
    this.apiService.getShopTree().subscribe(shop=> {
      this.categories = shop.shopCategories
      this.cmsContents = shop.cmsContents
    })
  }
}
