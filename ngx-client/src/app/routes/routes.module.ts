import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router'
import { DeactivableComponent } from "./deactivable.component";
import { DeactivableGuard } from "./deactivable-guard";
import { ThemeComponent } from "../templates/theme/theme.component";
import { CategoryComponent } from "../templates/category/category.component";
import { CmsContentComponent } from "../templates/cms-content/cms-content.component";
import { ProductComponent } from "../templates/product/product.component";
const routes: Routes = [
  {
    path: "category/:id",
    component: CategoryComponent,
    canDeactivate: [DeactivableGuard],
    children: [
      {
				path: "",
				redirectTo: "category",
				pathMatch: 'full' 
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
  providers: [DeactivableGuard]
})
export class RoutesModule { }
