import { Component, OnInit, ViewChild } from '@angular/core';
import { MatDialogRef, MatHorizontalStepper, MatStep } from '@angular/material';
import { CustomerService } from '@ngnx/thelia/api';
import { Customer, addressToCustomer, Address } from '@ngnx/thelia/model';
import {
  FormBuilder,
  FormControl,
  FormGroup,
  Validators,
  ValidatorFn,
  ValidationErrors,
  AbstractControl
} from '@angular/forms';
import { SEND, CANCEL, NEXT } from '@ngnx/thelia/common';
@Component({
  selector: 'connection-form',
  templateUrl: './connection-form.component.html',
  styleUrls: ['./connection-form.component.css'],
  host: { class: 'light' }
})
export class ConnectionFormComponent implements OnInit {

  @ViewChild(MatHorizontalStepper)
  stepper: MatHorizontalStepper
  cancelLabel: string = CANCEL;
  sendLabel: string = SEND;

  isClient: boolean = true;
  customer: Customer = {
    email: '',
    motdepasse: ''
  };
  errors: {
    e1: string
    e2: string
    p1: string
    p2: string
  } = {
      e1: null,
      e2: null,
      p1: null,
      p2: null
    }

  setIsClient(value) {
    this.isClient = value
    this.sendLabel = value ? SEND : NEXT
  }
  data: any = {
    email: "test1@ketmie.com", email2: "test1@ketmie", password: "dev1234", password2: "",
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
  /*
  data: any = { email: '', email2: '', password: '', password2: '', address: {} };
 
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
  connectForm: FormGroup;
  submitable: boolean = false;

  createForm: FormGroup;

  constructor(
    public dialogRef: MatDialogRef<ConnectionFormComponent>,
    private customerService: CustomerService,
    formBuilder: FormBuilder
  ) {
    this.createFormGroups(formBuilder);
  }

  private lastCheckedEmail: string;

  private createFormGroups(fb: FormBuilder) {
    const customer = this.customer;
    this.connectForm = fb.group({
      email: new FormControl('', [Validators.required, Validators.email]),
      password: new FormControl('', [Validators.required, Validators.minLength(6)])
    });
    this.connectForm.statusChanges.subscribe(value => {
      this.valideChange(this.connectForm);
    })

    const validateMails = (update: boolean = true) => {
      this.errors.e2 = (this.data.email == this.data.email2) ? null : "Les emails doivent correspondrent."
      if (update && this.createForm) {
        this.createForm.controls.email2.updateValueAndValidity()
        this.createForm.controls.email2.markAsTouched()
      }
    }
    const validatePwd = (update: boolean = true) => {
      this.errors.p2 = (this.data.password == this.data.password2) ? null : "Les mots de passe doivent correspondrent."
      if (update && this.createForm) {
        this.createForm.controls.password2.updateValueAndValidity()
        this.createForm.controls.password2.markAsTouched()
      }
    }
    this.createForm = fb.group({
      email: new FormControl(this.data.email, (c: AbstractControl) => {
        const v = c.value
        if (!v || !v.length) {
          this.errors.e1 = "email requis."
          return { required: true }
        }
        let e = Validators.email(c)
        if (!e) {
          if (this.blackList.indexOf(v) > -1) {
            this.emailTaken = true
            e = { taken: true }
          }
          else {
            this.emailTaken = false
          }
          this.checkTaken()
        }
        else
          this.errors.e1 = e ? "email invalid." : null
        validateMails()
        return e
      }),
      email2: new FormControl(this.data.email2, (c: AbstractControl) => {
        validateMails(false)
        return this.errors.e2 ? { equals: true } : null
      }),
      password: new FormControl(this.data.password, (c: AbstractControl) => {
        const v = c.value
        if (!v || !v.length) {
          this.errors.p1 = "mot de passe requis."
          return { required: true }
        }
        let e = null
        if (v.length < 6) {
          e = { toShort: v.length }
        }
        this.errors.p1 = e ? `Mot de pase trop court (${e.toShort}/6)` : null
        validatePwd()
        return e
      }),
      password2: new FormControl(this.data.password2, (c: AbstractControl) => {
        validatePwd(false)
        return this.errors.p2 ? { equals: true } : null
      }),
      emailTaken: new FormControl('', (c: AbstractControl) => {
        return this.emailTaken ? { taken: true } : null
      })
    });


    this.createForm.statusChanges.subscribe(status => {
      if (this.createForm.valid) {
        if (this.lastCheckedEmail != this.data.email) {
          this.lastCheckedEmail = this.data.email;
          let sub = this.customerService.getEmailTaken(this.data.email).subscribe(taken => {
            this.emailTaken = taken;
            if (taken) {
              this.blackList.push(this.data.email)
            }
            this.checkTaken(true)
            sub.unsubscribe();
          });
        }
      }
    });
  }

  private checkTaken(updateMail: boolean = false) {
    if(! this.createForm)
      return
    if (this.emailTaken) {
      this.errors.e1 = 'Cet email est déjà associé à un compte.'
      if(updateMail) {
        this.createForm.controls.email.updateValueAndValidity()
        this.createForm.controls.email.markAsTouched()
      }
    }
    else {
      if (this.createForm.controls.email.valid)
        this.errors.e1 = null
    }
    this.createForm.controls.emailTaken.updateValueAndValidity()
    this.createForm.controls.emailTaken.markAsTouched()
  }
  private blackList: string[] = []

  private _validAddress: Address;

  addressValid(address: Address) {
    this._validAddress = address;
  }

  emailTaken: boolean = true;
  emailTakenError: string;

  private validatePassword: ValidatorFn = (c: AbstractControl): ValidationErrors | null => {
    const value: string = c.value;
    if (value == null || !value.length) return null;
    if (this.data.password != this.data.password2) return { equals: true };
    return null;
  };

  private validateEmail: ValidatorFn = (c: AbstractControl): ValidationErrors | null => {
    const value: string = c.value;
    if (value == null || !value.length) return null;
    if (this.data.email2 != this.data.email) return { equals: true };
    return null;
  };

  private valideChange(formGroup: FormGroup) {
    this.submitable = formGroup.valid;
  }

  selectionChange(event: {
    selectedIndex: number
  }) {
    this.sendLabel = event.selectedIndex == 0 ? NEXT : SEND
  }

  canNext() {
    if (this.isClient)
      return this.connectForm.valid
    const stp: MatHorizontalStepper = this.stepper
    if (!stp)
      return false
    if (stp.selectedIndex == 0)
      return this.createForm.valid
    return Boolean(this._validAddress)
  }
  ngOnInit() { }

  next() {
    if (this.isClient)
      return this.submit()

    const stp: MatHorizontalStepper = this.stepper
    if (stp.selectedIndex == 0) {
      stp.next()
    }
    else {
      let customer: Customer = {
        email: this.data.email,
        motdepasse: this.data.password
      };
      addressToCustomer(this._validAddress, customer);
      let sub = this.customerService.createCustomer(customer).subscribe(customer => {
        this.dialogRef.close(customer);
      });
    }
  }
  private loginError: string;
  submit() {
    this.loginError = undefined;
    this.customerService.login(this.customer).subscribe(customer => {
      if (customer && customer.loggedIn) {
        return this.dialogRef.close(customer);
      }
      this.loginError = 'Email ou mot de passe invalide.';
      this.data.password = this.data.password2 = ""
      this.connectForm.updateValueAndValidity()
    })
  }
}
