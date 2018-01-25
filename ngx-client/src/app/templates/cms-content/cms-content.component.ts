import { Component, AfterViewInit, ElementRef, ViewChild, OnDestroy } from '@angular/core';
import { DeactivableComponent } from "../../routes/deactivable.component";
import { Observable, Observer, Subscription } from "rxjs";
import { ApiService } from "../../api/api.service";
import { CMSContent } from "../../api/api.model";
import { ActivatedRoute } from "@angular/router";

@Component({
  selector: 'cms-content',
  templateUrl: './cms-content.component.html',
  styleUrls: ['./cms-content.component.css']
})
export class CmsContentComponent extends DeactivableComponent implements AfterViewInit, OnDestroy {

  constructor(
    private api: ApiService,
    private route: ActivatedRoute) {
    super()
    console.log('CmsContentComponent')
  }

  @ViewChild("ctn")
  private ctnRef: ElementRef | undefined

  enabled: boolean = false
  title: string = undefined
  loading: boolean = true
  private routeSub: Subscription

  deactivate(): Observable<boolean> {
    return Observable.create((o: Observer<boolean>) => {
      this.enabled = false
      setTimeout(() => {
        o.next(true)
        o.complete()
      }, 300);
    })
  }
  ngOnDestroy() {
    this.routeSub.unsubscribe()
  }

  ngAfterViewInit() {
    const ctn: HTMLElement = this.ctnRef.nativeElement
    this.routeSub = this.route.params.subscribe(params => {
      console.log("params.subscribe", params.id)
      this.loading = true
      const id: string = params.id
      let cmsContent: CMSContent = this.api.getCmsContentById(id)
      if (!cmsContent) {
        let sub = this.api.getShopTree()
          .subscribe(tree => {
            this.createContent(this.api.getCmsContentById(id), ctn)
          },
          err => { },
          () => {
            if (sub)
              sub.unsubscribe()
          })
      }
      else
        this.createContent(cmsContent, ctn)
    })
  }

  private createContent(cmsContent: CMSContent, target: HTMLElement) {
    const done = () => {
      let sub = this.api.getDescription("cms-content", cmsContent.id)
      .subscribe(description=>{
        const target: HTMLElement = this.ctnRef.nativeElement
        target.innerHTML = description
        this.title = cmsContent.label
        this.enabled = true
        this.loading = false
      },err=>{

      },
      () => {
        if(sub) {
          sub.unsubscribe()
          sub = null
        }
      })
      if(sub && sub.closed)
        sub.unsubscribe()
    }
    if(this.enabled) {
      this.enabled = false
      setTimeout(() => {
        done()
      }, 300);
      return
    }
    done()
  }
}
