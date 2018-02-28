import { Pipe, PipeTransform, OnDestroy } from '@angular/core';
import { CustomerService } from '@ngnx/thelia/api';
import { Country } from '@ngnx/thelia/model';
import { Subscription } from 'rxjs';
@Pipe({
  name: 'country'
})
export class CountryPipe implements PipeTransform, OnDestroy {
  private countries: Country[];
  private sub: Subscription;
  constructor(service: CustomerService) {
    this.sub = service.getCountries().subscribe(countries => {
      this.countries = countries;
    });
  }

  ngOnDestroy() {
    this.sub.unsubscribe();
  }

  transform(value: any, args?: any): any {
    if (this.countries) {
      let country = this.countries.find(country => {
        return country.id == value;
      });
      if (country) return country.name;
    }
    return null;
  }
}
