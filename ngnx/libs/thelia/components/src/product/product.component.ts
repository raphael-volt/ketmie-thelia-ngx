import { Component, OnInit, OnDestroy, ViewChild } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import {
  ApiService,
  CardService,
  DeclinationService,
  findCategoryIdInURL,
  replaceProductIdInURL
} from '@ngnx/thelia/api';
import { Category, Product, ProductDetail, IDeclinationItem } from '@ngnx/thelia/model';
import { Subscription, Observable, Observer } from 'rxjs';
import { SliderBaseComponent } from '../slider-base.component';
import { ImgBoxDirective } from './img-box.directive';
import { SliderEvent } from '../slider.directive';
@Component({
  selector: 'product',
  templateUrl: './product.component.html',
  styleUrls: ['./product.component.css']
})
export class ProductComponent extends SliderBaseComponent implements OnInit, OnDestroy {
  @ViewChild(ImgBoxDirective) imgBox: ImgBoxDirective;

  boxProduct: ProductDetail = undefined;

  loaded: boolean;
  declinationId: string = null;

  canAddToCard: boolean;
  product: ProductDetail;
  productPrice: string;
  canNavImages: boolean = false;
  prevProductId: string;
  nextProductId: string;
  private routeSubscription: Subscription;

  constructor(
    private decli: DeclinationService,
    public card: CardService,
    private api: ApiService,
    private route: ActivatedRoute,
    private router: Router
  ) {
    super();
  }

  keybordHandler = (event: KeyboardEvent) => {
    let newId = null;
    let arrow = [false, false];
    switch (event.code) {
      case 'ArrowLeft':
        newId = this.prevProductId;
        arrow[0] = true;
        break;

      case 'ArrowRight':
        arrow[1] = true;
        newId = this.nextProductId;
        break;

      case 'Escape':
        this.close();
        break;

      default:
        break;
    }
    if (newId && !event.altKey && !event.shiftKey) {
      this._productIdChanged = true;
      this.router.navigate([replaceProductIdInURL(this.router.url, newId)]);
    }
    if (newId && event.shiftKey) {
      if (arrow[0]) this.imgBox.prev();
      else this.imgBox.next();
    }
  };
  protected slideInEnded() {
    this.boxProduct = this.product;
    this.canNavImages = this.product.images.length > 1;
  }

  private getNearestProduct(id: string, dir: number): Product {
    const c: Category = this.api.getCategoryById(this.categoryId);
    if (!c) return null;
    const products = c.children;
    let p = products.find(p => p.id == id);
    if (!p) return null;

    let i: number = products.indexOf(p);
    p = null;
    if (i != -1) {
      i += dir;
      const n = products.length;
      if (i > n - 1) i = 0;
      if (i < 0) i = n - 1;
      p = products[i];
    }
    return p;
  }
  deactivate() {
    return Observable.create(o => {
      if (this.imgBox) {
        let subscribed = false;
        let sub: Subscription = null;
        const done = () => {
          if (sub) {
            sub.unsubscribe();
            subscribed = true;
          }
          o.next(true);
          o.complete();
        };
        if (!this._productIdChanged) sub = super.deactivate().subscribe(done);
        else sub = this.imgBox.close().subscribe(done);
      } else return super.deactivate();
    });
  }
  private _productIdChanged: boolean = false;

  imRowNavClick(id: string) {
    this._productIdChanged = true;
  }

  close() {
    this._productIdChanged = false;
    let subscribed: boolean = false;
    let sub: Subscription = null;
    let done = () => {
      subscribed = false;
      sub = null;
      sub = this.route.parent.url.subscribe(value => {
        if (sub) {
          sub.unsubscribe();
          sub = null;
        }
        if (!subscribed) {
          subscribed = true;
          let l = value.map(segment => String(segment.path));
          this.router.navigate(l);
        }
      });
      if (subscribed && sub) sub.unsubscribe();
    };
    if (this.imgBox) {
      this.imgBox.close();
    }
    done();
  }

  decliItemId: string;

  declinationChange(value: IDeclinationItem) {
    if (value) {
      this.canAddToCard = true;
      this.productPrice = value ? value.price : undefined;
    }
  }

  categoryId: string;
  private category: Category;

  addToCard() {
    let item = this.card.createItem(this.product, this.declinationId);
    this.card.add(item, () => {
      console.log('added to card', item);
    });
  }

  hasDeclination = false;
  ngOnInit() {
    document.addEventListener('keyup', this.keybordHandler);
    let sub = this.route.parent.params.subscribe(params => {
      this.routeSubscription = this.route.params.subscribe(params => {
        let apiSub = this.api.getProductDescription(params.id).subscribe(product => {
          if (product.description == '') product.description = null;

          this.product = product;
          this.hasDeclination = this.decli.declined(product);
          this.canAddToCard = product && !this.hasDeclination;
          this.productPrice = this.hasDeclination ? undefined : product.price;
          if (this.sliderState == 'none') {
            this.slideIn();
          } else {
            this.canNavImages = product.images.length > 1;
            this.boxProduct = product;
          }

          this.categoryId = findCategoryIdInURL(this.router.url);
          this.category = this.api.getCategoryById(this.categoryId);
          this.nextProductId = this.getNearestProduct(product.id, 1).id;
          this.prevProductId = this.getNearestProduct(product.id, -1).id;
        });
      });
      if (sub) {
        sub.unsubscribe();
        sub = null;
      }
    });
    if (sub && !sub.closed) {
      sub.unsubscribe();
    }
  }

  ngOnDestroy() {
    document.removeEventListener('keyup', this.keybordHandler);
    if (this.routeSubscription) this.routeSubscription.unsubscribe();
  }
}
