import { Component, OnInit } from '@angular/core';
import { MatDialogRef } from "@angular/material";
import { Address } from "../../api/api.model";
import { CREATE, SAVE, CANCEL } from "../../shared/button-labels";
@Component({
  selector: 'address-modal',
  templateUrl: './address-modal.component.html',
  styleUrls: ['./address-modal.component.css']
})
export class AddressModalComponent implements OnInit {

  constructor(private ref: MatDialogRef<AddressModalComponent, Address>) { }

  saveLabel: string = SAVE
  cancelLabel: string = CANCEL
  address: Address = {}
  title: string = "Modifier une adresse"
  private _isNew: boolean = false
  
  get isNew(): boolean {
    return this._isNew
  }
  set isNew(value: boolean) {
    if(this._isNew == value)
      return
    this._isNew = value
    this.saveLabel = value ? CREATE : SAVE
    this.title = value ? "Créer une adresse":"Modifier une adresse"
  }
  ngOnInit() {
  }

  canSave: boolean = false

  private currentAddress: Address
  validChange(address: Address) {
    this.canSave = Boolean(address)
    this.currentAddress = address
  }

  cancel() {
    this.ref.close(null)
  }

  save() {
    Object.assign(this.address, this.currentAddress)
    this.ref.close(this.address)
  }

}
