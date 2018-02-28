import { Injectable, EventEmitter } from '@angular/core';
import { Http, Request, RequestMethod, Response } from '@angular/http';
import { Observer, Observable, Subscription } from 'rxjs';
import { map } from 'rxjs/operators';
import { RequestService, ContentTypes, ImgTypes } from './request.service';
import { SessionService } from './session.service';
import {
  APIResponse,
  APISession,
  APIResponseError,
  isAPIResponseError,
  Category,
  Product,
  ProductDetail,
  CMSContent,
  ShopTree,
  Customer,
  IDescriptable
} from '@ngnx/thelia/model';

const PATH_CATEGORY: string = 'category';
const PATH_PRODUCT: string = 'product';
const PATH_SEP: string = '/';
const numInPart = /(\d+)(.*)?/;
const urlParts = (str: string) => {
  return str.split(PATH_SEP);
};
const urlJoin = (...parts) => parts.join(PATH_SEP);
const urlIdReplace = val => `${val}$2`;
const replaceUrlPartId = (input: string, id: string): string => input.replace(numInPart, urlIdReplace(id));
const asPart = (url: string | string[]) => {
  if (typeof url == 'string') {
    return urlParts(url);
  }
  return url;
};
const findPathParamIndex = (url: string | string[], name: string): number => {
  const l: string[] = asPart(url);
  const n: number = l.length;
  let i = l.indexOf(name);
  if (i > -1) {
    i++;
    if (i < n) {
      return i;
    }
  }
  return -1;
};
const findPathParam = (url: string | string[], name: string): string => {
  const l: string[] = asPart(url);
  const i = findPathParamIndex(l, name);
  if (i > -1) return l[i];
  return null;
};
const replaceIdInURL = (url: string, name: string, newId: string): string => {
  const l: string[] = asPart(url);
  const i = findPathParamIndex(l, name);
  if (i < 0) return null;
  l[i] = replaceUrlPartId(l[i], newId);
  return urlJoin.apply(null, l);
};
const findIdInURL = (url: string, name: string) => {
  let p = findPathParam(url, name);
  if (p) {
    if (numInPart.test(p)) {
      let m = numInPart.exec(p);
      return m[1];
    }
  }
  return null;
};

const findCategoryIdInURL = url => findIdInURL(url, PATH_CATEGORY);
const findProductIdInURL = url => findIdInURL(url, PATH_PRODUCT);
const replaceCategoryIdInURL = (url: string, id: string) => replaceIdInURL(url, PATH_CATEGORY, id);
const replaceProductIdInURL = (url: string, id: string) => replaceIdInURL(url, PATH_PRODUCT, id);

export { findCategoryIdInURL, findProductIdInURL, replaceCategoryIdInURL, replaceProductIdInURL, urlJoin };
@Injectable()
export class ApiService {
  private shopCategoriesMap: { [id: string]: any };
  private shopCategories: any[];

  get baseHref(): string {
    return this.request.baseHref;
  }

  constructor(private http: Http, private session: SessionService, private request: RequestService) {
    let sub = this.initialize().subscribe(tree => {
      sub.unsubscribe();
    });
  }

  private _initializing: boolean;
  private _initialized: boolean;

  initializedChange: EventEmitter<boolean> = new EventEmitter();

  get initialized(): boolean {
    return this._initialized;
  }
  private initializeObservers: Observer<ShopTree>[] = [];
  initialize(): Observable<ShopTree> {
    if (this._initialized) throw 'initialize error';
    if (this._initializing) {
      return Observable.create(observer => {
        this.initializeObservers.push(observer);
      });
    }
    this._initializing = true;
    const treeMap = tree => {
      this._initializing = false;
      this._initialized = true;
      this.initializedChange.emit(true);
      return tree;
    };
    if (this.session.hasSession) {
      this.request.setSessionId(this.session.sessionId);
      return this._getShopTree().pipe(map(treeMap));
    }
    return Observable.create((observer: Observer<ShopTree>) => {
      let sub = this.get(this.request.getRequest(this.request.getSessionParams())).subscribe(response => {
        const session: APISession = response.body;
        this.session.update(session.session_id);
        this.request.setSessionId(session.session_id);
        sub.unsubscribe();
        sub = this._getShopTree().subscribe(tree => {
          sub.unsubscribe();
          this.initializeObservers.push(observer);
          for (observer of this.initializeObservers) {
            observer.next(treeMap(tree));
            observer.complete();
          }
          this.initializeObservers.length = 0;
        });
      });
    });
  }
  private shopTreeRequesting = false;
  private shopTreeRequestingObservers: Observer<any>[] = [];
  private _shopTree: ShopTree = undefined;
  get hasShopTree(): boolean {
    return this._shopTree != undefined;
  }
  get shopTree(): ShopTree {
    return this._shopTree;
  }
  get(request: Request): Observable<APIResponse> {
    request.method = RequestMethod.Get;
    return this.http.request(request).pipe(map(response => response.json()));
  }

  post(request: Request): Observable<APIResponse> {
    request.method = RequestMethod.Post;
    return this.http.request(request).pipe(
      map(response => {
        let res = response.json();
        return res;
      })
    );
  }

  private _getShopTree(): Observable<ShopTree> {
    return Observable.create((observer: Observer<ShopTree>) => {
      const req = this.request.getRequest(this.request.getNavTreeParams());
      let sub = this.get(req).subscribe(response => {
        sub.unsubscribe();
        if (!response.success) {
          return observer.error(response.body);
        }
        this._shopTree = response.body;
        this.createCategoriesMap(this._shopTree.shopCategories);
        this.shopTreeRequestingObservers.unshift(observer);
        for (let o of this.shopTreeRequestingObservers) {
          o.next(this._shopTree);
          o.complete();
        }
        this.shopTreeRequestingObservers.length = 0;
      });
    });
  }

  getShopTree(): Observable<ShopTree> {
    if (this._shopTree) return Observable.of(this._shopTree);

    if (!this._initialized) {
      return Observable.create(o => {
        this.shopTreeRequestingObservers.push(o);
      });
    }

    return this._getShopTree();
  }

  getShopCategories(): Observable<Category[]> {
    return this.getShopTree().pipe(
      map(arbo => {
        return arbo.shopCategories;
      })
    );
  }

  private createCategoriesMap(categories: Category[]) {
    this.shopCategoriesMap = {};
    this.shopCategories = categories;
    for (let c of categories) this.shopCategoriesMap[c.id] = c;
  }

  getCategoryById(id): Category {
    return this.shopCategoriesMap ? this.shopCategoriesMap[id] : undefined;
  }

  getCmsContentById(id: string): CMSContent {
    let result: CMSContent;
    if (this._shopTree) {
      for (result of this._shopTree.cmsContents) {
        if (result.id == id) break;
        result = null;
      }
    }
    return result;
  }

  getProductDescription(id: string): Observable<ProductDetail> {
    const req = this.request.getRequest(this.request.getProductDescriptionParams(id));
    return this.get(req).pipe(
      map(res => {
        if (!res.success) throw res.body;
        return res.body;
      })
    );
  }

  getCmsContentDescription(id: string): Observable<IDescriptable> {
    const req = this.request.getRequest(this.request.getCmsContentDescriptionParams(id));
    return this.get(req).pipe(
      map(res => {
        if (!res.success) throw res.body;
        return res.body;
      })
    );
  }

  getCategoryDescription(id: string): Observable<IDescriptable> {
    const req = this.request.getRequest(this.request.getCmsContentDescriptionParams(id));
    return this.get(req).pipe(
      map(res => {
        if (!res.success) throw res.body;
        return res.body;
      })
    );
  }

  isErrorBody(response: APIResponse) {
    return isAPIResponseError(response.body);
  }
}
