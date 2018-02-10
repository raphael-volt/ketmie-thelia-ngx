import { Injectable, EventEmitter } from '@angular/core';
import { MediaChange, ObservableMedia, RESPONSIVE_ALIASES } from "@angular/flex-layout";
import { Subscription } from "rxjs";
import { Rect } from "./shared/math/rect";
export type LayoutSizes = [number, number]

const getLayoutSizes = (): LayoutSizes => {
  return [window.innerWidth, window.innerHeight]
}

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

export enum ScreenWidthMaxValues {
  "xs" = 600,
  "sm" = 960,
  "md" = 1280,
  "lg" = 1920,
  "xl" = 1920
}
const getMaxCurrentScreenWidth = () => {
  return Number(ScreenWidthMaxValues[currentAlias])
}

let currentAlias: ResponsiveAliases

const getMaxScreenWidth = (): number => {
  let size: number = NaN
  switch (currentAlias) {
    case "xs":
    case "sm":
    case "md":
    case "xl":
    case "lg":
    size = getMaxCurrentScreenWidth()
    break;
    
    default:
    break;
  }
  return size
}

const MENU_GAP_XS: number = 2;
const MENU_GAP_SM: number = 4;
const MENU_GAP_MD: number = 6;
const MENU_GAP_LG: number = 8;
const MENU_FONT_SIZE_XS: number = 20;
const MENU_FONT_SIZE_SM: number = 22;
const MENU_FONT_SIZE_MD: number = 24;
const MENU_FONT_SIZE_LG: number = 30;

const getImgBoxMaxHeight = () => {
  const l = getLayoutSizes()
  return l[1] - calculateMenuWidth()[0] * 2
}

const getImgBoxMaxWidth = () => {
  const maxLayoutWidth: number = getMaxScreenWidth()
  const menuW: number = calculateMenuWidth()[0]

  return (maxLayoutWidth - menuW) * .75
}

/**
 * get actual menu width
 * [menuW, gap, fontSize]
 * @returns [number, number, number]
 */
const calculateMenuWidth = (): [number, number, number]=> {
  let gap: number
  let fontSize: number
  let menuW: number

  switch (currentAlias) {
    case "xs":
      gap = MENU_GAP_XS
      fontSize = MENU_GAP_XS
      break;
    case "sm":
      gap = MENU_GAP_SM
      fontSize = MENU_GAP_SM
      break;
    case "xl":
    case "lg":
      gap = MENU_GAP_LG
      fontSize = MENU_GAP_LG
      break;
    case "md":
      gap = MENU_GAP_MD
      fontSize = MENU_GAP_MD
      break;
    default:
      break;
  }
  return [gap * 2 + fontSize, gap, fontSize]
}

export { getMaxScreenWidth, getImgBoxMaxWidth, getImgBoxMaxHeight }

@Injectable()
export class LayoutService {

  layout: LayoutSizes = [0, 0]
  layoutChange: EventEmitter<LayoutSizes> = new EventEmitter<LayoutSizes>()
  responsiveAliasChange: EventEmitter<ResponsiveAliases> = new EventEmitter<ResponsiveAliases>()


  constructor(media: ObservableMedia) {
    this.setLayoutSizes()
    window.addEventListener('resize', this.setLayoutSizes)
    media.subscribe(change => {
      if (change) {
        this._responsiveAlias = change.mqAlias as ResponsiveAliases
        currentAlias = this._responsiveAlias
        this.responsiveAliasChange.emit(this._responsiveAlias)
      }
    })
    for (const alias of [
      ResponsiveAliases.XS,
      ResponsiveAliases.SM,
      ResponsiveAliases.MD,
      ResponsiveAliases.LG,
      ResponsiveAliases.XL,
    ]) {
      if (media.isActive(alias)) {
        currentAlias = alias
        this._responsiveAlias = alias
        break
      }
    }
  }

  private _responsiveAlias: ResponsiveAliases

  get responsiveAlias(): ResponsiveAliases {
    return this._responsiveAlias
  }

  private setLayoutSizes = (event?: Event) => {
    this.layout = getLayoutSizes()
    this.layoutChange.emit(this.layout)
  }

}
