import { Injectable } from '@angular/core';
import { Http } from "@angular/http";
import { isDevMode } from '@angular/core';
import { Observer, Observable, Subscription } from "rxjs";
import { map } from "rxjs/operators";

import { Category, Product, ProductDetail, CMSContent, ShopTree } from "./api.model";

export type ImgTypes = "contenu" | "dossier" | "produit" | "rubrique"
export type ContentTypes = "category" | "product" | "cms-content"

const PATH_CATEGORY: string = "category"
const PATH_PRODUCT: string = "product"
const PATH_SEP: string = "/"
const numInPart = /(\d+)(.*)?/
const urlParts = (str: string) => {
  return str.split(PATH_SEP)
}
const urlJoin = (parts: string[]) => parts.join(PATH_SEP)
const urlIdReplace = val => `${val}$2`
const replaceUrlPartId = (input: string, id: string): string => input.replace(numInPart, urlIdReplace(id))
const asPart = (url: string | string[]) => {
  if (typeof url == "string") {
    return urlParts(url)
  }
  return url
}
const findPathParamIndex = (url: string | string[], name: string): number => {
  const l: string[] = asPart(url)
  const n: number = l.length
  let i = l.indexOf(name)
  if (i > -1) {
    i++
    if (i < n) {
      return i
    }
  }
  return -1
}
const findPathParam = (url: string | string[], name: string): string => {
  const l: string[] = asPart(url)
  const i = findPathParamIndex(l, name)
  if (i > -1)
    return l[i]
  return null
}
const replaceIdInURL = (url: string, name: string, newId: string): string => {
  const l: string[] = asPart(url)
  const i = findPathParamIndex(l, name)
  if (i < 0)
    return null
  l[i] = replaceUrlPartId(l[i], newId)
  return urlJoin(l)
}
const findIdInURL = (url: string, name: string) => {
  let p = findPathParam(url, name)
  if (p) {
    if (numInPart.test(p)) {
      let m = numInPart.exec(p)
      return m[1]
    }
  }
  return null
}

const findCategoryIdInURL = url => findIdInURL(url, PATH_CATEGORY)
const findProductIdInURL = url => findIdInURL(url, PATH_PRODUCT)
const replaceCategoryIdInURL = (url: string, id: string) => replaceIdInURL(url, PATH_CATEGORY, id)
const replaceProductIdInURL = (url: string, id: string) => replaceIdInURL(url, PATH_PRODUCT, id)

export { findCategoryIdInURL, findProductIdInURL, replaceCategoryIdInURL, replaceProductIdInURL }
@Injectable()
export class ApiService {

  private baseHref: string = ""
  private shopCategoriesMap: { [id: string]: any }
  private shopCategories: any[]

  constructor(private http: Http) {
    if (isDevMode())
      this.baseHref = "http://localhost:4501/"
  }

  getImageUrl(id: string, type: ImgTypes, width?: number, height?: number) {
    if (!width && !height) {
      throw new Error("Missing image dimensions.")
    }
    const args: string[] = ["id=" + id, "type=" + type]
    if (width)
      args.push("width=" + width)
    if (height)
      args.push("height=" + height)
    return this.baseHref + "image.php?" + args.join("&")
  }
  getProductImageUrlById(id: string, width?: number, height?: number) {
    return this.getImageUrl(id, "produit", width, height)
  }

  getProductImageUrl(id: string, width?: number, height?: number) {
    if (!width && !height) {
      throw new Error("Missing image dimensions.")
    }
    const args: string[] = ["produit=" + id]
    if (width)
      args.push("width=" + width)
    if (height)
      args.push("height=" + height)
    return this.baseHref + "image.php?" + args.join("&")
  }
  private shopTreeRequesting = false
  private shopTreeRequestingObservers: Observer<any>[] = []
  private shopTree: ShopTree

  getShopTree(): Observable<ShopTree> {
    if (this.shopTreeRequesting) {
      return Observable.create(o => {
        this.shopTreeRequestingObservers.push(o)
      })
    }
    if (this.shopTree)
      return Observable.of(this.shopTree)

    this.shopTreeRequesting = true
    return this.http.get(this.baseHref, {
      search: {
        fond: "api/arbo"
      }
    })
      .pipe(map(response => {
        this.shopTree = response.json()
        this.createCategoriesMap(this.shopTree.shopCategories)
        for (let o of this.shopTreeRequestingObservers) {
          o.next(this.shopTree)
          o.complete()
        }
        this.shopTreeRequestingObservers.length = 0
        this.shopTreeRequesting = false
        return this.shopTree
      }))
  }

  getShopCategories(): Observable<Category[]> {
    return this.getShopTree()
      .pipe(map(arbo => {
        return arbo.shopCategories
      }))
  }

  private createCategoriesMap(categories: Category[]) {
    this.shopCategoriesMap = {}
    this.shopCategories = categories
    for (let c of categories)
      this.shopCategoriesMap[c.id] = c
  }

  getCategoryById(id): Category {
    return this.shopCategoriesMap ? this.shopCategoriesMap[id] : undefined
  }

  getCategoryIdFromUrl(url: string) {
    const parts = url.split("/")
    const n: number = parts.length
    let i = parts.indexOf("category")
    if (i > -1) {
      i++
      if (i < n) {

      }

    }
  }

  getCmsContentById(id: string): CMSContent {
    let result: CMSContent
    if (this.shopTree) {
      for (result of this.shopTree.cmsContents) {
        if (result.id == id)
          break
        result = null
      }
    }
    return result
  }

  getProductDetails(id: string): Observable<ProductDetail> {
    return this.http.get(this.baseHref, {
      search: {
        fond: "api/product",
        id: id
      }
    }).pipe(
      map(request => {
        const result = request.json()
        if (result.error)
          throw new Error(result.error)
        return result
      })
      )
  }

  getDescription(type: ContentTypes, id: string): Observable<string> {
    let cmsContent: CMSContent = this.getCmsContentById(id)
    if (cmsContent && cmsContent.description)
      return Observable.of(cmsContent.description)

    return this.http.get(this.baseHref, {
      search: {
        fond: "api/descriptions",
        type: type,
        id: id
      }
    }).pipe(
      map(request => {
        const result = request.json()
        if (result.error)
          throw new Error(result.error)
        cmsContent.description = request.json().description
        return cmsContent.description
      })
      )
  }
}
