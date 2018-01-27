import { Injectable, EventEmitter } from '@angular/core';
import { MediaChange, ObservableMedia, RESPONSIVE_ALIASES } from "@angular/flex-layout";
import { Subscription } from "rxjs";
export type LayoutSizes = [number, number]

export enum ResponsiveAliases {
  XS = "xs", // (min-width: 0px) and (max-width: 599px)
  SM = "sm", // (min-width: 600px) and (max-width: 959px)
  MD = "md", // (min-width: 960px) and (max-width: 1279px)
  LG = "lg", // (min-width: 1280px) and (max-width: 1919px)
  XL = "xl", // (min-width: 1920px) and (max-width: 5000px)
  GT_XS = "gt-xs", // (min-width: 600px)
  LT_SM = "lt-sm", // (max-width: 599px)
  GT_SM = "gt-sm", // (min-width: 960px)
  LT_MD = "lt-md", // (max-width: 959px)
  GT_MD = "gt-md", // (min-width: 1280px)
  LT_LG = "lt-lg", // (max-width: 1279px)
  GT_LG = "gt-lg", // (min-width: 1920px)
  LT_XL = "lt_xl" // (max-width: 1920px)
}

@Injectable()
export class LayoutService {

  layout: LayoutSizes = [0, 0]
  layoutChange: EventEmitter<LayoutSizes> = new EventEmitter<LayoutSizes>()
  responsiveAliasChange: EventEmitter<ResponsiveAliases> = new EventEmitter<ResponsiveAliases>()


  constructor(media: ObservableMedia) {
    this.setLayoutSizes()
    window.addEventListener('resize', this.setLayoutSizes)
    media.subscribe(change => {
      if(change) {
        this._responsiveAlias = change.mqAlias as ResponsiveAliases
        console.log("LayoutService.responsiveAlias", this._responsiveAlias, this.layout)
        this.responsiveAliasChange.emit(this._responsiveAlias)
      }
    })
    for(const alias of [
      ResponsiveAliases.XS,
      ResponsiveAliases.SM,
      ResponsiveAliases.MD,
      ResponsiveAliases.LG,
      ResponsiveAliases.XL,
    ]) {
      if(media.isActive(alias)) {
        this._responsiveAlias = alias
      }
    }
  }
  
  private _responsiveAlias: ResponsiveAliases
  
  get responsiveAlias(): ResponsiveAliases {
    return this._responsiveAlias
  }
  
  private setLayoutSizes = (event?: Event) => {
    console.log("LayoutService.setLayoutSizes", this._responsiveAlias, this.layout)
    this.layout[0] = window.innerWidth
    this.layout[1] = window.innerHeight
    this.layoutChange.emit(this.layout)
  }

}
