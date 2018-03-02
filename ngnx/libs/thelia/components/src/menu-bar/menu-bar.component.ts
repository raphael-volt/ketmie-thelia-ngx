import { Component, ElementRef, EventEmitter, Output} from '@angular/core';
import { CustomerService, CardService } from '@ngnx/thelia/api';
@Component({
  selector: '[menu-bar]',
  templateUrl: './menu-bar.component.html',
  styleUrls: ['./menu-bar.component.css'],
  host: {
    '(click)': 'barClickHandler($event)'
  }
})
export class MenuBarComponent {
  constructor(private target: ElementRef, public cardService: CardService, public customerService: CustomerService) { }

  @Output() barClick: EventEmitter<void> = new EventEmitter();

  @Output() home: EventEmitter<boolean> = new EventEmitter<boolean>();

  @Output() menu: EventEmitter<boolean> = new EventEmitter<boolean>();

  @Output() customer: EventEmitter<boolean> = new EventEmitter<boolean>();

  @Output() card: EventEmitter<boolean> = new EventEmitter<boolean>();

  barClickHandler(event: MouseEvent) {
    if (event.target == this.target.nativeElement) 
      this.barClick.emit();
  }
}
