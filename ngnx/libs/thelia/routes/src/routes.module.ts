import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router';

import { CatalogGuard, CustomerGuard, DeactivableGuard } from '@ngnx/thelia/guards';
import {
  CategoryComponent,
  CmsContentComponent,
  ProductComponent,
  CustomerComponent,
  CardComponent
} from '@ngnx/thelia/components';
const routes: Routes = [
  {
    path: '',
    canActivate: [CatalogGuard],
    children: [
      {
        path: 'category/:id',
        component: CategoryComponent,
        canDeactivate: [DeactivableGuard],
        children: [
          {
            path: '',
            redirectTo: 'category',
            pathMatch: 'full',
            canDeactivate: [DeactivableGuard]
          },
          {
            path: 'product/:id',
            component: ProductComponent,
            canDeactivate: [DeactivableGuard]
          }
        ]
      },
      {
        path: 'cms-content/:id',
        component: CmsContentComponent,
        canDeactivate: [DeactivableGuard]
      },
      {
        path: 'customer',
        component: CustomerComponent,
        canActivate: [CustomerGuard],
        canDeactivate: [DeactivableGuard]
      },
      {
        path: 'card',
        component: CardComponent,
        canDeactivate: [DeactivableGuard]
      }
    ]
  },
  { path: '**', redirectTo: '' }
];

@NgModule({
  imports: [CommonModule, RouterModule.forRoot(routes)],
  declarations: [],
  exports: [RouterModule],
  providers: [DeactivableGuard, CatalogGuard, CustomerGuard]
})
export class RoutesModule {}
