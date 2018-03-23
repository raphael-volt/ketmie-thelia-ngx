import { Component, OnInit, Input, OnChanges, SimpleChanges, ViewChild } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from "@angular/forms";
import { Customer, Address, customerToAddress, FR_ID } from '@ngnx/thelia/model';
import { CustomerService } from '@ngnx/thelia/api';
import { one } from '@ngnx/thelia/utils';

import { MatHorizontalStepper } from "@angular/material";

@Component({
  selector: 'div [order-stepper]',
  templateUrl: './order-stepper.component.html',
  styleUrls: ['./order-stepper.component.css']
})
export class OrderStepperComponent implements OnInit, OnChanges {

  constructor(
    private formBuilder: FormBuilder,
    private customerService: CustomerService
  ) { }

  @ViewChild(MatHorizontalStepper)
  stepper: MatHorizontalStepper

  deliveryFG: FormGroup
  transportFG: FormGroup
  paymentFG: FormGroup
  deliveryAddresses = []
  transports = []

  ngOnInit() {
    this.deliveryFG = this.formBuilder.group({
      address: [null, Validators.required]
    })
    this.transportFG = this.formBuilder.group({
      transport: [null, Validators.required]
    })
    this.paymentFG = this.formBuilder.group({
      payement: [null, Validators.required]
    })
    this.customerService.getAdresses().subscribe(addresses => {
      const a = customerToAddress(this.customerService.customer)
      a.libelle = "Mon adresse"
      this.deliveryAddresses = [a].concat(addresses)
    })
  }

  ngOnChanges(changes: SimpleChanges) {
    
  }
}
