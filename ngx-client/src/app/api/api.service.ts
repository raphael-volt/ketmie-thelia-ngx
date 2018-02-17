import { Injectable } from '@angular/core';
import {
  Http, Headers,
  Request, RequestMethod, RequestOptions, RequestOptionsArgs,
  Response, ResponseContentType
} from "@angular/http";
import { LocalStorageService } from "angular-2-local-storage";
import { isDevMode } from '@angular/core';
import { Observer, Observable, Subscription } from "rxjs";
import { map } from "rxjs/operators";

import {
  APIResponse, APIResponseError, isAPIResponseError,
  Category, Product, ProductDetail, CMSContent, ShopTree, Customer
} from "./api.model";

type Session = {
  session_id: string
  customer: Customer
}
const API: string = "api"
export type ImgTypes = "contenu" | "dossier" | "produit" | "rubrique"
export type ContentTypes = "category" | "product" | "cms-content"

const PATH_CATEGORY: string = "category"
const PATH_PRODUCT: string = "product"
const PATH_SEP: string = "/"
const numInPart = /(\d+)(.*)?/
const urlParts = (str: string) => {
  return str.split(PATH_SEP)
}
const urlJoin = (...parts) => parts.join(PATH_SEP)
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
  return urlJoin.apply(null, l)
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

export { findCategoryIdInURL, findProductIdInURL, replaceCategoryIdInURL, replaceProductIdInURL, urlJoin }
@Injectable()
export class ApiService {

  private _baseHref: string = ""
  private shopCategoriesMap: { [id: string]: any }
  private shopCategories: any[]

  private _http: HttpService
  get http(): HttpService {
    return this._http
  }
  constructor(http: Http, storage: LocalStorageService) {
    if (isDevMode())
      this._baseHref = "http://localhost:4501/"
    this._http = new HttpService(http, this._baseHref, storage);
  }
  get baseHref(): string {
    return this._baseHref
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
    return this._baseHref + "image.php?" + args.join("&")
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
    return this._baseHref + "image.php?" + args.join("&")
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
    const req = this.http.getRequest(this.http.getSearchParam('arbo'))
    return this.http.get(req)
      .pipe(map(res => {
        if (!res.success) {
          throw res.body
        }
        this.shopTree = res.body
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
    const req = this.http.getRequest(this.http.getSearchParam('product', { id: id }))
    return this.http.get(req).pipe(
      map(res => {
        if (!res.success)
          throw res.body
        return res.body
      })
    )
  }

  getDescription(type: ContentTypes, id: string): Observable<string> {
    let cmsContent: CMSContent = this.getCmsContentById(id)
    if (cmsContent && cmsContent.description)
      return Observable.of(cmsContent.description)

    const req = this.http.getRequest(
      this.http.getSearchParam('product', { id: id, type: type }
      ))
    return this.http.get(req).map(response => {
      if (!response.success)
        throw response.body
      cmsContent.description = response.body
      return cmsContent.description
    })
  }

  isErrorBody(response: APIResponse) {
    return isAPIResponseError(response.body)
  }

  getApiResponse(response: Response): APIResponse {
    return response.json()
  }

}

type SessionCookie = {
  session_id: string
}

const X_API_SESSION_ID: string = "X-Api-Session-Id"
const COOKIE_ID: string = "api-session"

export class HttpService {
  private _sessionCookie: SessionCookie

  get hasSession() {
    console.log("HttpService.hasSession", this._sessionCookie)
    return this._sessionCookie != undefined && (this._sessionCookie.session_id.length > 0)
  }

  registerSession(): Observable<boolean> {
    return this.get(
      this.getRequest(this.getSearchParam("session"))
    ).pipe(
      map(response => {
        const session: Session = response.body
        this.setSessionId(session.session_id)
        return true
      })
    )
  }
  private headers: Headers = new Headers({

  })

  private loadSessionCookie() {
    let cookie = this.storage.get<SessionCookie>(COOKIE_ID)
    if (!cookie) {
      cookie = { session_id: "" }
      this.storage.set(COOKIE_ID, cookie)
    }
    this._sessionCookie = cookie
    console.log("HttpService.cookie", cookie)
    this.setSessionHeader()
  }

  constructor(
    public http: Http,
    private _baseHref: string,
    private storage: LocalStorageService) {
    this.loadSessionCookie()
  }
  getSearchParam(serviceName: string, input?: any): URLSearchParams {
    if (!input)
      input = {}
    input.fond = urlJoin(API, serviceName)
    return input
  }

  currentCustomer(): Observable<Customer> {
    return this.get(
      this.getRequest(
        this.getSearchParam('customer', { method: "current" })
      )
    ).pipe(
      map(response => response.body)
    )
  }

  login(customer: Customer): Observable<Customer> {
    let req = this.getRequest(
      this.getSearchParam('customer', { method: "login" }),
      customer
    )
    return this.post(req).pipe(
      map(response => {
        if (response.success) {
          Object.assign(customer, response.body)
        }
        customer.loggedIn = response.success
        return customer
      })
    )
  }

  logout(): Observable<APIResponse> {
    return this.get(
      this.getRequest(
        this.getSearchParam("customer", { method: "logout" })
      ))
  }


  get(request: Request): Observable<APIResponse> {
    request.method = RequestMethod.Get
    return this.http.request(request).pipe(
      map(response => response.json())
    )
  }
  post(request: Request): Observable<APIResponse> {
    request.method = RequestMethod.Post
    return this.http.request(request).pipe(
      map(response => {
        let res = response.json()

        return res
      })
    )
  }

  getRequest(search: URLSearchParams = null, body: any = null) {
    return new Request({
      headers: this.headers,
      url: this._baseHref,
      responseType: ResponseContentType.Json,
      body: body,
      search: search
    })
  }

  private setSessionHeader() {
    this.headers.set(
      X_API_SESSION_ID,
      this._sessionCookie.session_id)
  }

  private setSessionId(value: string) {
    this._sessionCookie.session_id = value
    this.storage.set(COOKIE_ID, this._sessionCookie)
    this.setSessionHeader()
    console.log('HttpService?headers[X-Api-Session-Id]', this.headers.get("X-Api-Session-Id"))
  }
}