import { Subscription, Observable, Observer } from 'rxjs';
export class SubscriptionCollector {
  private _subs: Subscription[] = [];

  set add(subsciption: Subscription) {
    this._subs.push(subsciption);
  }

  unsubscribeAll = (...args: any[]) => {
    if (!this._subs.length) return;
    for (let s of this._subs) s.unsubscribe();
    this._subs.length = 0;
  };
}

const one = <T>(observable: Observable<T>, next: (t: T) => void): void => {
  let sub: Subscription;
  let emitterSub: Subscription;
  let obsSub: Subscription;
  let b = false;
  const obs: Observable<T> = Observable.create(o => {
    emitterSub = observable.subscribe(t => {
      o.next(t);
      o.complete();
      next(t);
    });
    return () => {
      if (sub && !sub.closed) {
        sub.unsubscribe();
      }
      if (emitterSub && !emitterSub.closed) {
        emitterSub.unsubscribe();
      }
    };
  });

  sub = obs.subscribe();

  if (b) {
    if (sub && !sub.closed) {
      sub.unsubscribe();
    }
    if (emitterSub && !emitterSub.closed) {
      emitterSub.unsubscribe();
    }
  }
};

export { one };
