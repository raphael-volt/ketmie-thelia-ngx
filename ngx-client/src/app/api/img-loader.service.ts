import { Injectable } from '@angular/core';
import { Observer, Observable, Subscription } from "rxjs";
import { ApiService, ImgTypes } from "./api.service";
import { LoaderEvent } from "../shared/events/loader-event";
import { XHRImage } from "../shared/utils/xhr-image";
@Injectable()
export class ImgLoaderService {

  constructor(private api: ApiService) { }

  private cache: string[] = []
  private loader: XHRImage = new XHRImage

  loadList(imgs: HTMLImageElement[], urls: string[]) {

  }


  getProductImages(ids: string[], w?: number, h?: number): Observable<HTMLImageElement[]> {
    let urls: string[] = ids.map(id => {
      return this.api.getProductImageUrl(id, w, h)
    })
    return this.loadUrls(urls)
  }
  getProductImage(id: string, w?: number, h?: number): Observable<HTMLImageElement> {
    return this.loadUrl(this.api.getProductImageUrl(id, w, h))
  }
  
  getProductImageById(imgId: string, w?: number, h?: number) {
    return this.getImageById(imgId, "produit", w, h)
  }

  getImageById(id: string, type: ImgTypes, w?: number, h?: number) {
    return this.loadUrl(this.api.getImageUrl(id, type, w, h))
  }

  private loadUrl(url: string, img?: HTMLImageElement) {
    return Observable.create((observer: Observer<HTMLImageElement>) => {
      if (this.cache.indexOf(url) > -1) {
        if (!img)
          img = new Image()
        const load = () => {
          img.removeEventListener('load', load)
          observer.next(img)
          observer.complete()
        }
        img.addEventListener('load', load)
        img.src = url
        return
      }
      const sub = this.loader.load(new Image(), url)
        .subscribe(
        event => {
          if (event.complete) {
            this.cache.push(url)
            observer.next(event.target)
          }
        },
        e => { },
        () => {
          sub.unsubscribe()
          observer.complete()
        })
    })
  }
  private loadUrls(urls: string[]) {
    return Observable.create((observer: Observer<HTMLImageElement[]>) => {
      urls = urls.slice(0)
      const imgs: HTMLImageElement[] = []
      const n = urls.length
      const loader: XHRImage = new XHRImage()
      let sub: Subscription
      const next = () => {
        if (sub)
          sub.unsubscribe()
        if (urls.length) {
          sub = this.loadUrl(urls.shift())
            .subscribe(
            img => {
              imgs.push(img)
              observer.next(imgs)
              next()
            },
            e => { },
            () => {
              sub.unsubscribe()
            }
            )
        }
        else {
          observer.complete()
        }
      }
    })
  }
}
