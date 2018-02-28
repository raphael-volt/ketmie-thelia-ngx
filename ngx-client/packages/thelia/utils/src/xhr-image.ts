import { LoaderEvent } from "@thelia/events";
import { Observable, Observer } from "rxjs";
export class XHRImage {

  constructor(public xhr?: XMLHttpRequest) { }

  load(img: HTMLImageElement, url: string): Observable<LoaderEvent> {

    return Observable.create((o: Observer<LoaderEvent>) => {
      const loaderEvent: LoaderEvent = new LoaderEvent(img)
      let xhr: XMLHttpRequest = this.xhr
      if (!xhr) {
        xhr = new XMLHttpRequest()
      }
      const error = err => {
        console.log('XHRImage/loadevent/error')
        console.error(err)
        unhandle()
        o.error(err)
      }

      const progress = (e: ProgressEvent) => {
        if (isNaN(loaderEvent.total))
          loaderEvent.total = e.total
        loaderEvent.loaded = e.loaded
        o.next(loaderEvent)
      }
      
      const loadend = e => {
        unhandle()
        const fr: FileReader = new FileReader()
        const imgDone = () => {
          img.removeEventListener('load', imgDone)
          loaderEvent.loaded = loaderEvent.total
          loaderEvent.complete = true
          o.next(loaderEvent)
          o.complete()
        }
        const dataDone = () => {
          fr.removeEventListener('load', dataDone)
          img.addEventListener('load', imgDone)
          img.src = fr.result
        }
        fr.addEventListener('load', dataDone)

        fr.readAsDataURL(xhr.response)
      }

      const unhandle = () => {
        xhr.removeEventListener("progress", progress)
        xhr.removeEventListener("loadend", loadend)
        xhr.removeEventListener("error", error)
      }
      xhr.addEventListener("progress", progress)
      xhr.addEventListener("loadend", loadend)
      xhr.addEventListener("error", error)
      xhr.open('get', url)
      xhr.responseType = "blob"
      xhr.send(null)
    })
  }
}