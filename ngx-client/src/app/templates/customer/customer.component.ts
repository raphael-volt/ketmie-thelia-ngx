import { Component, OnInit, AfterViewInit, OnDestroy } from '@angular/core';
import { MatSnackBar } from '@angular/material';
import { Router } from "@angular/router";
import { Customer, Address, customerToAddress } from "../../api/api.model";
import { CustomerService } from "../../api/customer.service";
import { SliderBaseComponent } from "../slider-base.component";
import { Subscription, Observable, Observer } from "rxjs";
import { map } from "rxjs/operators";
import {
  FormBuilder, FormGroup, FormControl, Validators,
  AbstractControl, ValidationErrors, ValidatorFn
} from "@angular/forms";

@Component({
  selector: 'customer',
  templateUrl: './customer.component.html',
  styleUrls: ['./customer.component.css'],
  host: {
    'class': 'light'
  }
})
export class CustomerComponent extends SliderBaseComponent implements OnInit, AfterViewInit, OnDestroy {

  emailGroup: FormGroup
  pwdGroup: FormGroup
  data: any = { email: "", email2: "", password: "", password2: "" }
  customer: Customer = {}
  address: Address = {}
  deliveryAddresses: Address[] = []
  canChangeEmail = false
  emailTaken = false
  constructor(
    router: Router,
    private snackBar: MatSnackBar,
    private customerService: CustomerService,
    formBuilder: FormBuilder) {
    super()
    
    this.createFormGroups(formBuilder)
    this.goHome = () => {
      router.navigate(["/"])
    }
  }

  private goHome: () => void
  
  ngOnDestroy() {
    
  }


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
    if (this.data.email == this.customer.email)
      return { 'notChanged': true }
    if (this.data.email2 != this.data.email)
      return { 'equals': true }
    return null
  }

  getEmailError(control: FormControl) {
    if (control.errors.required)
      return `L'email doit être vérifié.`

    if (control.errors.notChanged === true)
      return `L'email n'est pas modifié.`

    if (control.errors.equals === true)
      return `Les emails doivent être identique.`
  }

  getPwdError(control: FormControl) {
    if (control.errors.required)
      return `Le mot de passe doit être vérifié.`

    if (control.errors.equals === true)
      return `Les mots de passe doivent être identique.`
  }

  createFormGroups(fb: FormBuilder) {
    this.emailGroup = fb.group({
      email: new FormControl(this.data.email, [Validators.required, Validators.email]),
      email2: new FormControl(this.data.email2, [Validators.required, this.validateEmail])
    })
    this.pwdGroup = fb.group({
      password: new FormControl(this.data.password, [Validators.required, Validators.minLength(6)]),
      password2: new FormControl(this.data.password2, [Validators.required, this.validatePassword])
    })
    this.emailGroup.statusChanges.subscribe(status => {
      if (this.emailGroup.valid) {
        let sub = this.customerService.getEmailTaken(this.data.email).subscribe(taken => {
          sub.unsubscribe()
          this.emailTaken = taken
          this.canChangeEmail = !taken
        })
      }
      else {
        this.emailTaken = false
        this.canChangeEmail = false
      }
    })
  }

  changeEmail() {
    let sub = this.customerService.changeEmail(this.data.email)
      .subscribe(success => {
        this.data.email2 = ""
        this.canChangeEmail = false
        this.openSnackBar(success ? "Votre email est modifié." : "La modification de votre email a échouée.", success ? "" : "Erreur")
        if (success)
          this.customer.email = this.data.email
        else {
          // @TODO error notification
          console.error("changeEmail customer FAIL")
        }
        this.emailGroup.reset()
      })
  }

  changePassword() {
    let sub = this.customerService.changePassword(this.data.password)
      .subscribe(success => {
        this.data.password = ""
        this.data.password2 = ""
        this.pwdGroup.reset()
        if (!success) {
          // @TODO error notification
          console.error("changePassword customer FAIL")
        }
        this.openSnackBar(success ? "Votre mot de passe est modifié." : "La modification de votre mot de passe a échouée.", success ? "" : "Erreur")
      })
  }

  ngOnInit() {
    this.customer = this.customerService.customer
    this.data.email = this.customer.email
    this.address = customerToAddress(this.customer)
  }

  ngAfterViewInit() {
    let sub = this.customerService.getAdresses().subscribe(adresses => {
      sub.unsubscribe()
      this.deliveryAddresses = adresses
      this.slideIn()
    })
    if(this.customer.isNew) {
      this.openSnackBar(`Bienvenu ${this.customer.prenom} ${this.customer.nom}`)
      this.customer.isNew = false
    }
  }

  saveCustomer() {
    let sub = this.customerService.updateCustomer(this.address)
      .subscribe(success => {
        if (success) {
          Object.assign(this.customer, this.address)
        }
        this.openSnackBar(success ? "Votre compte est modifié." : "La modification de votre compte a échouée.", success ? "" : "Erreur")
      })
  }

  deleteAccount() {

  }

  logout() {
    let sub = this.customerService.logout()
      .subscribe(response => {
        this.openSnackBar("Vous n'êtes plus connecté.")
        this.goHome()
      })
  }

  openSnackBar(message, action = '') {
    this.snackBar.open(message, action, {
      duration: 2500,
      verticalPosition: "top",
      horizontalPosition: "center"
    });
  }
}

import { Directive, ElementRef, Output, EventEmitter, HostListener } from "@angular/core";

@Directive({
  selector: '[blurChild]'
})
export class BlurChildDirective {

  @Output()
  blurOut: EventEmitter<any> = new EventEmitter()

  private focused = false
  @HostListener('focusin')
  focusIn = () => {
    this.focused = true
  }
  @HostListener('focusout')
  focusOut = () => {
    this.focused = false
    setTimeout(() => {
      if (!this.focused)
        this.blurOut.emit(true)
    }, 10)
  }
}

