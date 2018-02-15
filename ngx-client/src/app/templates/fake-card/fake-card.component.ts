import { Component, ElementRef, OnInit, Input, OnChanges, SimpleChanges } from '@angular/core';

@Component({
  selector: 'fake-card',
  templateUrl: './fake-card.component.html',
  styleUrls: ['./fake-card.component.css']
})
export class FakeCardComponent implements OnInit, OnChanges {

  @Input()
  theme
  ngOnChanges(changes: SimpleChanges) {
    if(changes.theme) {
      this.themeChange()
    }
  }

  private updateClasses(theme: string, data: {
    "k-light": boolean,
    "k-dark": boolean
  }) {
    data['k-light'] = (theme == "k-light")
    data['k-dark'] = !data['k-light']
  }
  
  themeChange(updateOp: boolean = true) {
    if(this.theme != "k-light" && this.theme != "k-dark") {
      // ?
      return
    }
    this.updateClasses(this.theme, this.mainClass)
    if(updateOp) {
      const theme: string = this.theme == "k-light" ? "k-dark":"k-light"
      this.optheme = theme
      this.updateClasses(theme, this.optheme)
    } 
  }

  mainClass: {
    "k-light": boolean,
    "k-dark": boolean
  } = {
    "k-dark": false,
    "k-light": false
  }
  opthemeClass: {
    "k-light": boolean,
    "k-dark": boolean
  } = {
    "k-dark": false,
    "k-light": false
  }
  optheme
  
  constructor(ref: ElementRef) { 
    // this.findCurrentTheme(ref.nativeElement)
  }
  private updateOpTheme() {
    this.opthemeClass["k-light"] = ! this.mainClass["k-light"]
    this.opthemeClass["k-dark"] = ! this.mainClass["k-dark"]
  }
  findCurrentTheme(parent: HTMLElement) {
    if(! parent) {
      this.theme = "k-light"
      this.mainClass["k-light"] = true
      this.mainClass["k-dark"] = false
      this.updateOpTheme()
      return
    }
    let cl = parent.classList
    if(cl.contains("k-light")) {
      this.theme = "k-light"
      this.mainClass["k-light"] = true
      this.mainClass["k-dark"] = false
      this.updateOpTheme()
      return
    }
    if(cl.contains("k-dark")) {
      this.theme = "k-dark"
      this.mainClass["k-dark"] = true
      this.mainClass["k-light"] = false
      this.updateOpTheme()
      return
    }
    // this.findCurrentTheme(parent.parentElement)
  }
  
  toggleTheme() {
    this.theme = this.theme == "k-dark" ? "k-light":"k-dark"
    this.themeChange()
  }
  toggleoptTheme() {
    this.optheme = this.optheme == "k-dark" ? "k-light":"k-dark"
    this.updateClasses(this.optheme, this.opthemeClass)
  }
  ngOnInit() {
  }

}
