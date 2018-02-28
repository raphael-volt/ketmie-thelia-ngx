import { Component, OnInit, Input, OnChanges, SimpleChanges, Output, EventEmitter } from '@angular/core';
import { FormBuilder, FormGroup, FormControl, Validators, AbstractControl, ValidationErrors } from '@angular/forms';
import { raisonsMap } from '../raison.pipe';
import { CustomerService } from '@ngnx/thelia/api';
import { Address, Country } from '@ngnx/thelia/model';
import { CANCEL, SAVE } from '@ngnx/thelia/common';

const numericValidator = Validators.pattern(/^(\d+)$/);

const isEmptyInputValue = (value: any): boolean => {
  // we don't check for string here so it also works with arrays
  return value == null || value.length === 0;
};

@Component({
  selector: 'address-form',
  templateUrl: './address.component.html',
  styleUrls: ['./address.component.css']
})
export class AddressComponent implements OnInit, OnChanges {
  @Output() save: EventEmitter<Address> = new EventEmitter();
  @Output() close: EventEmitter<any> = new EventEmitter();

  @Output() valid: EventEmitter<Address> = new EventEmitter();
  private _valid: boolean = false;

  @Input() address: Address;

  @Input() saveButton = true;

  @Input() saveButtonLabel = SAVE;

  @Input() cancelButton = true;

  @Input() cancelButtonLabel = CANCEL;

  data: Address = {};

  formGroup: FormGroup;
  nameGroup: FormGroup;
  defaultCountry: string = '64';

  requiredError = 'Ce champ est requis.';

  raisons: { id: string; view: string }[] = [];

  canSave: boolean = false;

  countries: Country[];

  constructor(service: CustomerService, formBuilder: FormBuilder) {
    this.createForm(formBuilder);
    let sub = service.getCountries().subscribe(countries => {
      this.countries = countries;
      if (sub) sub.unsubscribe();
    });
    if (this.countries) sub.unsubscribe();

    for (const id in raisonsMap) {
      this.raisons.push({
        id: id,
        view: raisonsMap[id].long
      });
    }
  }

  private createForm(fb: FormBuilder) {
    const data = this.data;
    this.nameGroup = fb.group({
      libelle: new FormControl(data.libelle, [Validators.required])
    });
    this.formGroup = fb.group({
      raison: new FormControl(data.raison, [Validators.required]),
      entreprise: new FormControl(data.entreprise, []),
      nom: new FormControl(data.nom, [Validators.required]),
      prenom: new FormControl(data.prenom, [Validators.required]),
      adresse1: new FormControl(data.adresse1, [Validators.required]),
      adresse2: new FormControl(data.adresse2, []),
      adresse3: new FormControl(data.adresse3, []),
      cpostal: new FormControl(data.cpostal, [Validators.required]),
      ville: new FormControl(data.ville, [Validators.required]),
      tel: new FormControl(data.tel, [Validators.required, numericValidator]),
      pays: new FormControl(data.pays, [Validators.required])
    });
    this.nameGroup.statusChanges.subscribe(status => {
      if (this.isCustomerAddress) return;
      this.checkChanges();
    });
    this.formGroup.statusChanges.subscribe(this.checkChanges);
  }
  private checkChanges = (status?) => {
    let changed: boolean = false;
    const d = this.data;
    const a = this.address;
    for (const p in this.data) {
      if (d[p] != a[p]) {
        changed = true;
        break;
      }
    }
    let nameValid: boolean = this.isCustomerAddress ? true : this.nameGroup.valid;
    if (this.formGroup.valid && nameValid) {
      this.canSave = changed;
    } else this.canSave = false;
    if (this._valid != this.canSave) {
      this._valid = this.canSave;
      this.valid.emit(this._valid ? this.data : null);
    }
  };
  ngOnInit() {}

  @Input() isCustomerAddress: boolean;

  ngOnChanges(changes: SimpleChanges) {
    if (changes.address) {
      if (changes.address.currentValue) {
        Object.assign(this.data, this.address);
      } else this.data = {};
      //this.isCustomerAddress = (this.data as Object).hasOwnProperty('libelle')
    }
  }
  saveHandler() {
    Object.assign(this.address, this.data);
    this.canSave = false;
    this.save.emit(this.address);
  }
  cancelHandler() {
    Object.assign(this.data, this.address);
    this.canSave = false;
    this.close.emit();
  }
}
