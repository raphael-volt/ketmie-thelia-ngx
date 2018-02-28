import { Injectable, TemplateRef } from '@angular/core';
import { ComponentType } from '@angular/cdk/portal';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA, MatDialogConfig } from '@angular/material';
import { defaultDialogConfig } from '@ngnx/mat-env';
import {
  DialogContentComponent,
  DialogConfig,
  DialogResult
} from './components/dialog-content/dialog-content.component';
import { Observable } from 'rxjs';
@Injectable()
export class PopupService {
  constructor(private service: MatDialog) {}

  dialog(config: DialogConfig, matConfig?: MatDialogConfig): Observable<DialogResult> {
    if (!matConfig) matConfig = defaultDialogConfig;
    const ref = this.service.open(DialogContentComponent, matConfig);
    ref.componentInstance.config = config;

    return ref.afterClosed();
  }

  open<T, D = any>(
    componentOrTemplateRef: ComponentType<T> | TemplateRef<T>,
    config?: MatDialogConfig<D>
  ): MatDialogRef<T> {
    if (!config) config = defaultDialogConfig;
    return this.service.open(componentOrTemplateRef, config);
  }

  afterClose<T, D = any>(
    componentOrTemplateRef: ComponentType<T> | TemplateRef<T>,
    config?: MatDialogConfig<D>
  ): Observable<D> {
    return this.open(componentOrTemplateRef, config).afterClosed();
  }
}
