import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router'
import { DeactivableGuard } from "./deactivable-guard";
import { CatalogGuard } from "./catalog-guard";
import { DeactivableComponent } from "./deactivable.component";
import { ThemeComponent } from "../templates/theme/theme.component";
import { CategoryComponent } from "../templates/category/category.component";
import { CmsContentComponent } from "../templates/cms-content/cms-content.component";
import { ProductComponent } from "../templates/product/product.component";
const routes: Routes = [
  {
    path: "category/:id",
    component: CategoryComponent,
    canActivate: [CatalogGuard],
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
    path: "theme",
    component: ThemeComponent 
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
  providers: [DeactivableGuard, CatalogGuard]
})
export class RoutesModule { }
