import { Component } from '@angular/core';
import { Observable } from "rxjs";
@Component({
  selector: 'app-deactivable',
  template: '<span><span>',
  styleUrls: []
})
export class DeactivableComponent {

  deactivate(): Observable<boolean> {
    return Observable.of(true)
  }

}
