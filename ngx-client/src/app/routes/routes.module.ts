import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router'

import { DeactivableGuard } from "./deactivable-guard";
import { CatalogGuard } from "./catalog-guard";
import { CustomerGuard } from "./customer-guard";
import { DeactivableComponent } from "@thelia/common";
import { 
  CategoryComponent, 
  CmsContentComponent, 
  ProductComponent, 
  CustomerComponent, 
  CardComponent 
} from "@thelia/components";
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
