import { Component, OnInit } from '@angular/core';
import { MatDialogRef } from '@angular/material';
import { FormBuilder, FormControl, FormGroup, Validators, ValidatorFn, ValidationErrors, AbstractControl } from '@angular/forms';
import { CustomerService } from "@thelia/api";
import { Customer, addressToCustomer, Address } from "@thelia/model";
import { SEND, CANCEL } from "@thelia/common";
@Component({
  selector: 'connection-form',
  templateUrl: './connection-form.component.html',
  styleUrls: ['./connection-form.component.css'],
  host: { 'class': 'light' }
})
export class ConnectionFormComponent implements OnInit {

  cancelLabel: string = CANCEL
  sendLabel: string = SEND
  
  isClient: boolean = true
  customer: Customer = {
    email: "",
    motdepasse: ""
  }
  data: any = { email: "", email2: "", password: "", password2: "", address: {} }
  /*
  
  DEBUG ONLY 

  data: any = {
    email: "test1@ketmie.com", email2: "test1@ketmie.com", password: "dev1234", password2: "dev1234",
    address: {
      raison: "3",
      entreprise: "",
      nom: "Test",
      prenom: "Nouveau",
      adresse1: "address",
      adresse2: "complement",
      adresse3: "",
      cpostal: "66222",
      ville: "",
      tel: "0202020202",
      pays: "64"
    }
  }
  */
  connectForm: FormGroup
  submitable: boolean = false

  createForm: FormGroup

  constructor(
    public dialogRef: MatDialogRef<ConnectionFormComponent>,
    private customerService: CustomerService,
    formBuilder: FormBuilder) {
    this.createFormGroups(formBuilder)
  }

  private lastCheckedEmail: string

  private createFormGroups(fb: FormBuilder) {
    const customer = this.customer
    this.connectForm = fb.group({
      email: new FormControl("", [Validators.required, Validators.email]),
      password: new FormControl("", [Validators.required, Validators.minLength(6)])
    })
    this.connectForm.statusChanges.subscribe(value => {
      this.valideChange(this.connectForm)
    })
    this.createForm = fb.group({
      email: new FormControl(this.data.email, [Validators.required, Validators.email]),
      email2: new FormControl(this.data.email2, [Validators.required, this.validateEmail]),
      password: new FormControl(this.data.password, [Validators.required, Validators.minLength(6)]),
      password2: new FormControl(this.data.password2, [Validators.required, this.validatePassword])
    })
    this.createForm.statusChanges.subscribe(status => {
      if (this.createForm.valid) {
        if(this.lastCheckedEmail != this.data.email) {
          this.lastCheckedEmail = this.data.email
          let sub = this.customerService.getEmailTaken(this.data.email)
            .subscribe(taken => {
              this.emailTaken = taken
              this.emailTakenError = taken ? "Cet email est déjà associé à un compte." : undefined
              sub.unsubscribe()
            })

        }
      }
    })
  }

  private _validAddress: Address
  addressValid(address: Address) {
    this.submitable = (address != null)
    this._validAddress = address
  }

  emailTaken: boolean = true
  emailTakenError: string

  private validatePassword: ValidatorFn = (c: AbstractControl): ValidationErrors | null => {
    const value: string = c.value
    if (value == null || !value.length)
      return null
    if (this.data.password != this.data.password2)
      return { 'equals': true }
    return null
  }

  private validateEmail: ValidatorFn = (c: AbstractControl): ValidationErrors | null => {
    const value: string = c.value
    if (value == null || !value.length)
      return null
    if (this.data.email2 != this.data.email)
      return { 'equals': true }
    return null
  }

  getEmailError(control: FormControl) {
    if (control.errors.required)
      return `L'email doit être vérifié.`

    if (control.errors.equals === true)
      return `Les emails doivent être identique.`
  }

  getPwdError(control: FormControl) {
    if (control.errors.required)
      return `Le mot de passe doit être vérifié.`

    if (control.errors.equals === true)
      return `Les mots de passe doivent être identique.`
  }

  private valideChange(formGroup: FormGroup) {
    this.submitable = formGroup.valid
  }

  ngOnInit() {
  }

  private loginError: string
  submit() {
    if (this.isClient) {
      this.loginError = undefined
      this.customerService.login(this.customer).subscribe(customer => {
        if (customer && customer.loggedIn) {
          return this.dialogRef.close(customer)
        }
        this.loginError = "Email ou mot de passe invalide."
      })
    }
    else {
      let customer: Customer = {
        email: this.data.email,
        motdepasse: this.data.password
      }
      addressToCustomer(this._validAddress, customer)
      let sub = this.customerService.createCustomer(customer)
        .subscribe(customer => {
          this.dialogRef.close(customer)
        })
    }
  }

}
