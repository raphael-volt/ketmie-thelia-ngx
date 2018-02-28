import { NgModule } from '@angular/core';
import { CommonModule as NGCommonModule } from '@angular/common';

import { ApiModule } from "@thelia/api";
import { CommonModule } from "@thelia/common";
import { ComponentModule } from "@thelia/components";
import { MatModule } from "@thelia/mat";

@NgModule({
    imports: [
        NGCommonModule,
        CommonModule,
        ApiModule,
        MatModule,
        ComponentModule
    ],
    exports: [
        CommonModule,
        ApiModule,
        MatModule,
        ComponentModule
    ]
})
export class TheliaModule {

}