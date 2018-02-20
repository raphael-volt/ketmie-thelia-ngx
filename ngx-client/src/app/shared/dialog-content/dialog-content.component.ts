import { Component, OnInit } from '@angular/core';
import { MatDialogRef } from '@angular/material';

@Component({
  selector: 'dialog-content',
  templateUrl: './dialog-content.component.html',
  styleUrls: ['./dialog-content.component.css']
})
export class DialogContentComponent implements OnInit {

  constructor(private dialogRef: MatDialogRef<DialogContentComponent, DialogResult>) { }

  private _config: DialogConfig

  get config(): DialogConfig {
    return this._config
  }
  set config(value: DialogConfig) {
    if (this._config == value)
      return
    this._config = value
    this.validateConfig(value)

  }
  data: DialogConfig = {
    message: "",
    labels: {
      ok: {
        label: OK
      }
    }
  }
  ngOnInit() {
  }

  private validateConfig(config: DialogConfig) {
    if (!config) {
      this.data = {
        message: "?",
        labels: {
          ok: {
            label: OK
          }
        }
      }
      return
    }
    const data: DialogConfig = {
      message: config.message,
      cancelable: (config.cancelable === true)
    }
    data.message = config.message
    if (config.title)
      data.title = config.title
    if (config.labels) {
      data.labels = config.labels
      if (!data.labels.ok || !data.labels.ok.label) {
        data.labels.ok.label = OK
      }
    }
    if (data.cancelable) {
      if (!data.labels.cancel || !data.labels.cancel.label) {
        data.labels.cancel.label = CANCEL
      }
    }
    this.data = data
  }

  close() {
    this.dialogRef.close("ok")
  }

  cancel() {
    this.dialogRef.close("cancel")
  }
}

export interface DialogButtonLabel {
  label: string
  color?: "primary" | "accent" | "warn"
}

export interface DialogConfig {
  title?: string
  message: string
  labels?: {
    cancel?: DialogButtonLabel
    ok?: DialogButtonLabel
  }
  cancelable?: boolean
}

export type DialogResult = "ok" | "cancel"

const OK: string = "OK"
const YES: string = "Oui"
const NO: string = "Non"
const CANCEL: string = "Annuler"
const GIVE_UP: string = "Abandonner"
const CONTINUE: string = "Continuer"
const VALIDATE: string = "Valider"
const CLOSE: string = "Fermer"

export { OK, YES, NO, CLOSE, CANCEL, GIVE_UP, CONTINUE, VALIDATE }