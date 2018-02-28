import { TestBed, inject, async } from '@angular/core/testing';
import { HttpModule, Http, XHRBackend, BaseRequestOptions } from "@angular/http";
import { ApiModule } from "./api.module";
import { ApiService, replaceCategoryIdInURL, replaceProductIdInURL } from './api.service';
import { RequestService } from "./request.service";
import { SessionService } from "./session.service";
import { LocalStorageModule, LocalStorageService } from "angular-2-local-storage";
import { CardService } from './card.service';
import { DeclinationService } from './declination.service';
import { Card, ICard, ProductDeclination, CardItem, CardItemPerso, Product, ProductDetail } from "@thelia/model";
import { map } from "rxjs/operators";
let api: ApiService
let service: CardService
let products: ProductDetail[] = []
let decliProducts: ProductDetail[] = []
const cardI = (p: ProductDetail, dI, q = 1): CardItem => {
  return {
    productId: p.id,
    decliId: dI,
    quantity: q
  }
}
describe('CardService', () => { 
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        RequestService,
        DeclinationService,
        LocalStorageService,
        XHRBackend,
        BaseRequestOptions,
        {
          provide: Http,
          deps: [XHRBackend, BaseRequestOptions],
          useFactory: (xhr: XHRBackend, opt: BaseRequestOptions) => {
            return new Http(xhr, opt)
          }
        },
        {
          provide: SessionService,
          deps: [LocalStorageService],
          useFactory: (storage: LocalStorageService) => {
            return new SessionService(storage)
          }
        },
        {
          provide: ApiService,
          deps: [Http, RequestService, SessionService],
          useFactory: (h: Http, request: RequestService, session: SessionService) => {
            if (!api)
              api = new ApiService(h, session, request)
            return api
          }
        },
        {
          provide: CardService,
          deps: [ApiService, RequestService, DeclinationService],
          useFactory: (_api: ApiService, request: RequestService, decli: DeclinationService) => {
            if (!service)
              service = new CardService(_api, request, decli)
            return service
          }
        }
      ],
      imports: [
        HttpModule,
        ApiModule
      ]
    });
  });

  it('should be created', inject([CardService], (cardService: CardService) => {
    expect(service).toBeTruthy();
  }));
})
/*
describe('CardService', () => {

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [
        RequestService,
        LocalStorageService,
        XHRBackend,
        BaseRequestOptions,
        {
          provide: Http,
          deps: [XHRBackend, BaseRequestOptions],
          useFactory: (xhr: XHRBackend, opt: BaseRequestOptions) => {
            return new Http(xhr, opt)
          }
        },
        {
          provide: SessionService,
          deps: [LocalStorageService],
          useFactory: (storage: LocalStorageService) => {
            return new SessionService(storage)
          }
        },
        {
          provide: ApiService,
          deps: [Http, RequestService, SessionService],
          useFactory: (h: Http, request: RequestService, session: SessionService) => {
            if (!api)
              api = new ApiService(h, session, request)
            return api
          }
        },
        {
          provide: CardService,
          deps: [ApiService, RequestService],
          useFactory: (_api: ApiService, request: RequestService) => {
            if (!service)
              service = new CardService(_api, request)
            return service
          }
        }
      ],
      imports: [
        HttpModule,
        ApiModule
      ]
    });
  });

  it('should be created', inject([CardService], (cardService: CardService) => {
    expect(service).toBeTruthy();
  }));
  it("should get model", function (done) {
    const loadProducts = () => {
      const next = () => {

        if (products.length < 3 || decliProducts.length < 3) {
          let sub = api.getProductDescription(pIds.shift())
            .subscribe(desc => {
              sub.unsubscribe()
              if (desc.declinations && desc.declinations.length) {
                if (decliProducts.length < 3)
                  decliProducts.push(desc)
              }
              else {
                if (products.length < 3)
                  products.push(desc)
              }
              next()
            }
            )
        }
        else {
          expect(products.length).toEqual(3)
          expect(decliProducts.length).toEqual(3)
          if (typeof done == "function")
            done()
        }
      }
      let pIds: string[] = ["125", "25", "24", "98", "197", "95"]
      next()
    }
    if (api.hasShopTree)
      loadProducts()
    let sub = api.getShopTree()
      .subscribe(res => {
        sub.unsubscribe()
        loadProducts()
      })
  }, 2000)

  it("should have a card", async(() => {
    const d = () => {
      let card = service.card
      expect(card.total).toBeGreaterThanOrEqual(0)
      expect(card.items).toBeTruthy()
      expect(card.items.length).toBeGreaterThanOrEqual(0)
    }
    if (service.hasCard) {
      d()
    }
    else {
      service.get(c => {
        d()
      })
    }
  }))

  describe('simple product', () => {
    it('should clear card', async(() => {
      service.clear(card => {
        expect(service.card.items.length).toEqual(0)
        expect(service.card.total).toEqual(0)
        expect(card.items.length).toEqual(0)
        expect(card.total).toEqual(0)
      }
      )
    }))
    it('should add a product', async(() => {
      let p = products[0]
      const price = parseFloat(p.price)
      expect(price).toBeGreaterThan(0)
      let c = service.card
      const n = c ? c.items.length : 0
      const t = c ? c.total : 0

      service.add({
        productId: p.id,
        quantity: 1
      }, (ci: CardItem) => {
        expect(ci.productId).toEqual(p.id)
        expect(ci.index).toEqual(n)
        expect(n).toEqual(service.index(ci))
        expect(c.items.length).toEqual(n + 1)
        expect(c.total).toEqual(t + price)
      })
    }))

    it('should update a product', async(() => {
      const p = parseFloat(products[0].price)
      let c = service.card
      let item = c.items[0];
      expect(products[0].id).toEqual(item.productId)
      const nI = c.numItems
      const nA = c.numArticles
      const e_ni = nI
      const e_na = nA + 1
      const e_t = c.total + p
      const q = item.quantity
      item.quantity++
      expect(item.quantity).toEqual(q + 1)
      service.update(item, (i: CardItem) => {
        expect(service.numArticles).toEqual(e_na)
        expect(c.numArticles).toEqual(e_na)
        expect(service.length).toEqual(e_ni)
        expect(c.numItems).toEqual(e_ni)
        expect(service.total).toEqual(e_t)
        expect(c.total).toEqual(e_t)
        expect(service.card).toEqual(c)

      })
    }))

    it('should remove a product', async(() => {
      const p = parseFloat(products[0].price)
      const ci = service.at(0)
      let c = service.card
      let item = c.items[0];
      const nI = c.numItems
      const nA = c.numArticles
      const e_ni = nI - 1
      const e_na = nA - item.quantity
      const e_t = c.total - item.quantity * p
      service.remove(ci,
        item => {
          expect(c.items.length).toEqual(e_ni)
          expect(c.numItems).toEqual(e_ni)
          expect(c.numArticles).toEqual(e_na)
          expect(c.total).toEqual(e_t)
          expect(service.numItems).toEqual(e_ni)
          expect(service.numArticles).toEqual(e_na)
          expect(service.total).toEqual(e_t)
        })
    }))
  })

  describe('product decli', () => {
    it('should have declinaison', () => {
      let p = decliProducts[0]
      let did = p.declinations[0]
      let declis = service.getProductDecliItems(did)
      let dec = service.getProductDecli(did)
      let d = declis[0]
      expect(d).toBeTruthy()
      expect(d.price).toBeTruthy()
    })
    
    
    it('should add', async(() => {
      let p = decliProducts[0]
      let did = p.declinations[0]
      let declis = service.getProductDecliItems(did)
      let dec = service.getProductDecli(did)
      let d = declis[0];
      
      let ci: CardItem = cardI(p, d.id)

      service.add(ci, i => {
        expect(service.total).toEqual(Number(d.price))
      })
    }))
    it('should update price', async(() => {
      let p = decliProducts[0]
      let did = p.declinations[0]
      let declis = service.getProductDecliItems(did)
      let dec = service.getProductDecli(did)
      let d = declis[0];
      let cp = Number(d.price)
      let np = Number(declis[1].price)
      let ci: CardItem = service.at(0)
      ci.decliId = declis[1].id
      service.update(ci, i => {
        expect(service.total).toEqual(np)
      })
    }))
    it('should update price 2', async(() => {
      let p = decliProducts[0]
      let did = p.declinations[0]
      let declis = service.getProductDecliItems(did)
      let dec = service.getProductDecli(did)
      let d = declis[0];
      let cp = Number(declis[1].price)
      let np = Number(declis[2].price)
      let ci: CardItem = service.at(0)
      ci.decliId = declis[2].id
      service.update(ci, i => {
        expect(service.total).toEqual(np)
      })
    }))
    it('should refresh', async(() => {
      service.refresh(card => {
        let item = service.at(0)
        expect(item).toBeTruthy()
      })
    }))


  })

});
*/
