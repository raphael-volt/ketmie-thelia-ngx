import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router'

import { DeactivableGuard } from "./deactivable-guard";
import { CatalogGuard } from "./catalog-guard";
import { CustomerGuard } from "./customer-guard";

import { DeactivableComponent } from "./deactivable.component";
import { ThemeComponent } from "../templates/theme/theme.component";
import { CategoryComponent } from "../templates/category/category.component";
import { CmsContentComponent } from "../templates/cms-content/cms-content.component";
import { ProductComponent } from "../templates/product/product.component";
import { CustomerComponent } from "../templates/customer/customer.component";
import { CardComponent } from "../templates/card/card.component";
const routes: Routes = [
  {
    path: '',
    canActivate: [CatalogGuard],
    children: [
      {
        path: "category/:id",
        component: CategoryComponent,
        canDeactivate: [DeactivableGuard],
        children: [
          {
            path: "",
            redirectTo: "category",
            pathMatch: 'full',
            canDeactivate: [DeactivableGuard]
          },
          {
            path: "product/:id",
            component: ProductComponent,
            canDeactivate: [DeactivableGuard]
          }
        ]
      },
      {
        path: "cms-content/:id",
        component: CmsContentComponent,
        canDeactivate: [DeactivableGuard] 
      },
      {
        path: "customer",
        component: CustomerComponent,
        canActivate: [CustomerGuard],
        canDeactivate: [DeactivableGuard] 
      },
      {
        path: "card",
        canActivate: [CatalogGuard],
        component: CardComponent
      },
      {
        path: "theme",
        component: ThemeComponent 
      }
    ]
  },
  { path: '**', redirectTo: '' }
]

@NgModule({
  imports: [
    CommonModule,
    RouterModule.forRoot(routes)
  ],
  declarations: [DeactivableComponent],
  exports: [
    RouterModule,
    DeactivableComponent
  ],
  providers: [DeactivableGuard, CatalogGuard, CustomerGuard]
})
export class RoutesModule { }
