import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { ModelService } from "../model.service";
import { ModelComponent } from './model.component';

describe('ModelComponent', () => {
  let component: ModelComponent;
  let fixture: ComponentFixture<ModelComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      providers: [
        ModelService
      ],
      declarations: [ ModelComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ModelComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
