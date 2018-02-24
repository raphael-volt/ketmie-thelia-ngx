import { Component, OnInit, AfterViewInit, OnDestroy } from '@angular/core';
import { Router } from "@angular/router";
import { Customer, Address, customerToAddress, FR_ID } from "../../api/api.model";
import { CustomerService } from "../../api/customer.service";
import { SliderBaseComponent } from "../slider-base.component";
import { Subscription, Observable, Observer } from "rxjs";
import { map } from "rxjs/operators";
import {
  FormBuilder, FormGroup, FormControl, Validators,
  AbstractControl, ValidationErrors, ValidatorFn
} from "@angular/forms";
import { AddressModalComponent } from "../address-modal/address-modal.component";
import { PopupService } from "../../popup.service";
import { SEND, OK, CREATE, CANCEL } from "../../shared/button-labels";
import { raisonsMap } from "../raison.pipe";
import { SnackBarService } from "../../snack-bar.service";
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
  commands: any[] = []
  constructor(
    formBuilder: FormBuilder,
    router: Router,
    private modal: PopupService,
    private snackBar: SnackBarService,
    private customerService: CustomerService
  ) {
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
        this.canChangeEmail = false
        if (success) {
          this.data.email2 = ""
          this.openSnackBar("Votre email a été modifié.")
          this.emailGroup.markAsUntouched()
        }
        else {
          this.modalError("La modification de votre email a échouée.")
        }
      })
  }

  changePassword() {
    let sub = this.customerService.changePassword(this.data.password)
      .subscribe(success => {
        this.data.password = ""
        this.data.password2 = ""
        this.pwdGroup.reset()
        if (!success) {
          this.modalError("La modification de votre mot de passe a échouée.")
        }
        else
          this.pwdGroup.markAsUntouched()
        this.openSnackBar("Votre mot de passe a est modifié.")
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
    if (this.customer.isNew) {
      this.openSnackBar(`<i class="fa fa-smile-o" aria-hidden="true"></i> Bienvenue ${raisonsMap[this.customer.raison].long} ${this.customer.prenom} ${this.customer.nom}!`)
      this.customer.isNew = false
    }
  }

  saveCustomer() {
    let sub = this.customerService.updateCustomer(this.address)
      .subscribe(success => {
        if (success) {
          this.openSnackBar("Votre compte a est modifié.")
        }
        else {
          this.modalError("La modification de votre compte a échouée.")

        }
      })
  }

  private modalError(message: string, title?: string) {
    if (!title)
      title = "Erreur"
    let sub = this.modal.dialog({
      title: `<h2><i class="fa fa-bug k-light warn" aria-hidden="true"></i>${title}<h2>`,
      message: `<p class="k-light content raised warn pad-16 br-2">${message}</p>`,
      labels: {
        ok: {
          color: "primary",
          label: OK
        }
      }
    })
  }

  deleteAccount() {
    let sub = this.modal.dialog({
      title: "<h2>@TODO<h2>",
      message: `<i class="fa fa-frown-o" aria-hidden="true"></i> Cette fonctionnalité n'est pas encore implémentée.`,
      labels: {
        ok: {
          color: "primary",
          label: OK
        }
      }
    }).subscribe(result => {
      sub.unsubscribe()
    })
  }

  logout() {
    let sub = this.customerService.logout()
      .subscribe(response => {
        this.openSnackBar("Vous n'êtes plus connecté.")
        this.goHome()
      })
  }

  private openSnackBar(message, action?: string) {
    this.snackBar.show(message, action);
  }

  deleteDeliveryAddress(address: Address) {
    const i: number = this.deliveryAddresses.indexOf(address)
    let sub = this.customerService.deleteAddress(address)
      .subscribe(success => {
        sub.unsubscribe()
        if (success) {
          this.deliveryAddresses.splice(i, 1)
          this.openSnackBar("L'adresse a été supprimée.")
        }
        else
          this.modalError("La suppression de l'adresse a échouée.")
      })
  }

  editDeliveryAddress(address: Address) {
    let ref = this.modal.open(AddressModalComponent)
    let cmp = ref.componentInstance
    cmp.address = address
    cmp.isNew = false
    let sub = ref.afterClosed().subscribe(address => {
      sub.unsubscribe()
      if (address) {
        sub = this.customerService.updateAddress(address)
          .subscribe(success => {
            if (success) {
              this.openSnackBar("L'adresse à été modifiée.")
            }
            else {
              this.modalError("La modification de l'adresse a échouée.")
            }
          })
      }
      else
        this.openSnackBar("La modification de l'adresse a été annulée.")
    })
  }

  createDeliveryAddress() {
    let ref = this.modal.open(AddressModalComponent)
    let cmp = ref.componentInstance
    cmp.address = { client: this.customer.id, pays: FR_ID }
    cmp.isNew = true
    let sub = ref.afterClosed().subscribe(address => {
      sub.unsubscribe()
      if (address) {
        sub = this.customerService.createAddress(address)
          .subscribe(success => {
            if (success) {
              this.deliveryAddresses.push(address)
              this.openSnackBar("La nouvelle adresse à été ajoutée.")
            }
            else {
              this.modalError("La création de l'adresse a échouée.")
            }
          })
      }
      else
        this.openSnackBar("La création de l'adresse a été annulée.")
    })
  }
}

import { Directive, ElementRef, Output, EventEmitter, HostListener } from "@angular/core";

@Directive({
  selector: '[blurChild]'
})
export class BlurChildDirective implements OnDestroy {

  @Output()
  blurOut: EventEmitter<any> = new EventEmitter()

  private target: HTMLElement

  constructor(ref: ElementRef) {
    this.target = ref.nativeElement
    window.addEventListener('click', this.checkContais);
  }

  private checkContais = (e: Event) => {
    if (!this.target.contains(e.target as Node))
      this.blurOut.emit(true)
  }

  ngOnDestroy() {
    window.removeEventListener('click', this.checkContais);
  }
}

