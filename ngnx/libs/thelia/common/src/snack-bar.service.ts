import { Injectable } from '@angular/core';
import { MatSnackBar, MatSnackBarConfig, MatSnackBarRef } from '@angular/material';
import { SnackBarViewComponent } from './components/snack-bar-view/snack-bar-view.component';

const snackBarConfig: MatSnackBarConfig = {
  duration: 2500,
  verticalPosition: 'top',
  horizontalPosition: 'center'
};
@Injectable()
export class SnackBarService {
  private cmpRef: MatSnackBarRef<SnackBarViewComponent>;
  constructor(private snackBar: MatSnackBar) {}

  show(message: string, action?: string) {
    let ref: MatSnackBarRef<SnackBarViewComponent> = this.snackBar.openFromComponent(
      SnackBarViewComponent,
      snackBarConfig
    );
    ref.instance.message = message;
    ref.instance.action = action;
  }
}
