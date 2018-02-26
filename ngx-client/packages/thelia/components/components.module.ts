import { NgModule } from "@angular/core";
import { CommonModule } from "@angular/common";

import { AddressModalComponent } from './address/address-modal/address-modal.component'
import { AddressViewComponent } from './address/address-view/address-view.component'
import { AddressComponent } from './address/address.component'
import { CardComponent } from './card/card.component'
import { CategoryComponent } from './category/category.component'
import { CmsContentComponent } from './cms-content/cms-content.component'
import { CustomerComponent } from './customer/customer.component'
import { ConnectionFormComponent } from './customer/connection-form/connection-form.component'
import { ImRowComponent } from './im-row/im-row.component'
import { KetmieBackgroundComponent } from './ketmie-background/ketmie-background.component'
import { MenuBarComponent } from './menu-bar/menu-bar.component'
import { ProductComponent } from "./product/product.component";
@NgModule({

    declarations: [
        AddressModalComponent,
        AddressViewComponent,
        AddressComponent,
        CardComponent,
        CategoryComponent,
        CmsContentComponent,
        CustomerComponent,
        ImRowComponent,
        KetmieBackgroundComponent,
        MenuBarComponent,
        ProductComponent
    ],
    entryComponents: [
        ConnectionFormComponent
    ]
})
export class ComponentModule {

}