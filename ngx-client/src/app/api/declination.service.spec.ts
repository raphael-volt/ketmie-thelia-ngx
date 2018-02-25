import { TestBed, inject } from '@angular/core/testing';

import { DeclinationService } from './declination.service';

describe('DeclinationService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [DeclinationService]
    });
  });

  it('should be created', inject([DeclinationService], (service: DeclinationService) => {
    expect(service).toBeTruthy();
  }));
});
