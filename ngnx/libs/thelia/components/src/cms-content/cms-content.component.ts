import { Component, AfterViewInit, ViewChild, OnDestroy } from '@angular/core';
import { Observable, Observer, Subscription } from 'rxjs';
import { ApiService } from '@ngnx/thelia/api';
import { CMSContent } from '@ngnx/thelia/model';
import { ActivatedRoute } from '@angular/router';
import { SliderBaseComponent } from '../slider-base.component';

@Component({
  selector: 'cms-content',
  templateUrl: './cms-content.component.html',
  styleUrls: ['./cms-content.component.css']
})
export class CmsContentComponent extends SliderBaseComponent implements AfterViewInit, OnDestroy {
  constructor(private api: ApiService, private route: ActivatedRoute) {
    super();
  }

  title: string = undefined;
  loading: boolean = true;
  private routeSub: Subscription;

  ngOnDestroy() {
    this.routeSub.unsubscribe();
  }

  ngAfterViewInit() {
    this.routeSub = this.route.params.subscribe(params => {
      this.loading = true;
      const id: string = params.id;
      let cmsContent: CMSContent = this.api.getCmsContentById(id);
      if (!cmsContent) {
        let sub = this.api.getShopTree().subscribe(
          tree => {
            this.createContent(this.api.getCmsContentById(id));
          },
          err => {},
          () => {
            if (sub) sub.unsubscribe();
          }
        );
      } else this.createContent(cmsContent);
    });
  }

  description: string;
  private createContent(cmsContent: CMSContent) {
    const done = () => {
      let sub = this.api.getCmsContentDescription(cmsContent.id).subscribe(
        description => {
          this.description = description.description;
          this.title = cmsContent.label;
          this.slideIn();
          this.loading = false;
        },
        err => {},
        () => {
          if (sub) {
            sub.unsubscribe();
            sub = null;
          }
        }
      );
      if (sub && sub.closed) sub.unsubscribe();
    };
    done();
  }
}
