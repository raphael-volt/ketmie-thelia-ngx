import { Injectable, isDevMode } from '@angular/core';
import { Request, URLSearchParams, RequestMethod, ResponseContentType, Headers, Response } from '@angular/http';
import { APIResponse } from '@ngnx/thelia/model';
const API: string = 'api/';
const X_API_SESSION_ID: string = 'X-Api-Session-Id';

export type ImgTypes = 'contenu' | 'dossier' | 'produit' | 'rubrique';
export type ContentTypes = 'category' | 'product' | 'cms-content';

@Injectable()
export class RequestService {
  private _baseHref: string = '';
  get baseHref(): string {
    return this._baseHref;
  }

  private _headers: Headers;

  constructor() {
    if (isDevMode()) this._baseHref = 'http://localhost:4501/';
    this._headers = new Headers({});
  }

  setSessionId(id: string) {
    if (this._headers.has(X_API_SESSION_ID) && this._headers.get(X_API_SESSION_ID) == id) return;
    console.warn('[RequestService] X_API_SESSION_ID CHANGED');
    this._headers.set(X_API_SESSION_ID, id);
  }

  getSearchParam(serviceName: string, input?: any): URLSearchParams {
    if (!input) input = {};
    input.fond = API + serviceName;
    return input;
  }

  getRequest(search: URLSearchParams = null, body: any = null, method?: RequestMethod): Request {
    const request = new Request({
      search: search,
      headers: this._headers,
      url: this._baseHref,
      responseType: ResponseContentType.Json,
      body: body
    });
    if (method != undefined) {
      request.method = method;
    }
    return request;
  }

  getCustomerParams(
    method: 'login' | 'logout' | 'current' | 'address' | 'update' | 'create',
    input?: any
  ): URLSearchParams {
    if (!input) input = {};
    input.method = method;
    return this.getSearchParam('customer', input);
  }

  getSessionParams(): URLSearchParams {
    return this.getSearchParam('session');
  }

  getDescriptionParams(type: ContentTypes, id: string) {
    return this.getSearchParam('descriptions', { id: id, type: type });
  }

  getProductDescriptionParams(id: string): URLSearchParams {
    return this.getSearchParam('product', { id: id });
  }

  getCmsContentDescriptionParams(id: string): URLSearchParams {
    return this.getDescriptionParams('cms-content', id);
  }

  getCategoryDescriptionParams(id: string): URLSearchParams {
    return this.getDescriptionParams('category', id);
  }

  getNavTreeParams(): URLSearchParams {
    return this.getSearchParam('arbo');
  }

  getImageUrl(id: string, type: ImgTypes, width?: number, height?: number) {
    if (!width && !height) {
      throw new Error('Missing image dimensions.');
    }
    const args: string[] = ['id=' + id, 'type=' + type];
    if (width) args.push('width=' + width);
    if (height) args.push('height=' + height);
    return this._baseHref + 'image.php?' + args.join('&');
  }

  getDefaultProductImageUrl(productId: string, width?: number, height?: number) {
    return this.getImageUrl(productId, 'produit', width, height);
  }

  getProductImageUrl(id: string, width?: number, height?: number) {
    if (!width && !height) {
      throw new Error('Missing image dimensions.');
    }
    const args: string[] = ['produit=' + id];
    if (width) args.push('width=' + width);
    if (height) args.push('height=' + height);
    return this._baseHref + 'image.php?' + args.join('&');
  }
}
