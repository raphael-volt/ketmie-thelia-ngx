import { Component, OnInit } from '@angular/core';
import { MatDialogRef } from '@angular/material';
import { OK, CANCEL } from '../../button-labels';
@Component({
  selector: 'dialog-content',
  templateUrl: './dialog-content.component.html',
  styleUrls: ['./dialog-content.component.css']
})
export class DialogContentComponent implements OnInit {
  constructor(private dialogRef: MatDialogRef<DialogContentComponent, DialogResult>) {}

  private _config: DialogConfig;

  get config(): DialogConfig {
    return this._config;
  }
  set config(value: DialogConfig) {
    if (this._config == value) return;
    this._config = value;
    this.validateConfig(value);
  }
  data: DialogConfig = {
    message: '',
    labels: {
      ok: {
        label: OK
      }
    }
  };
  ngOnInit() {}

  private validateConfig(config: DialogConfig) {
    if (!config) {
      this.data = {
        message: '?',
        labels: {
          ok: {
            label: OK
          }
        }
      };
      return;
    }
    const data: DialogConfig = {
      message: config.message,
      cancelable: config.cancelable === true
    };
    data.message = config.message;
    if (config.title) data.title = config.title;
    if (config.labels) {
      data.labels = config.labels;
      if (!data.labels.ok || !data.labels.ok.label) {
        data.labels.ok.label = OK;
      }
    }
    if (data.cancelable) {
      if (!data.labels.cancel || !data.labels.cancel.label) {
        data.labels.cancel.label = CANCEL;
      }
    }
    this.data = data;
  }

  close() {
    this.dialogRef.close('ok');
  }

  cancel() {
    this.dialogRef.close('cancel');
  }
}

export interface DialogButtonLabel {
  label: string;
  color?: 'primary' | 'accent' | 'warn';
}

export interface DialogConfig {
  title?: string;
  message: string;
  labels?: {
    cancel?: DialogButtonLabel;
    ok?: DialogButtonLabel;
  };
  cancelable?: boolean;
}

export type DialogResult = 'ok' | 'cancel';
