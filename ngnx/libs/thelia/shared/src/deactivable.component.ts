import { Observable, Observer } from 'rxjs';

export class DeactivableComponent {
  protected setDeactivable() {
    const observer: Observer<boolean> = this.deactivator;
    if (!observer) return;
    observer.next(true);
    observer.complete();
    this.deactivator = null;
  }
  protected _deactivate() {}
  private deactivator: Observer<boolean>;
  deactivate(): Observable<boolean> {
    return Observable.create((observer: Observer<boolean>) => {
      this.deactivator = observer;
      this._deactivate();
    });
  }
}
