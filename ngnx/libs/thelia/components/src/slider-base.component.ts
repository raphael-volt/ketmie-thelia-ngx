import { DeactivableComponent } from '@ngnx/thelia/shared'
import { SliderDirection, SliderState, SliderEvent } from './slider.directive';
export class SliderBaseComponent extends DeactivableComponent {
  sliderState: SliderState = 'none';

  sliderEvents(event: SliderEvent) {
    if (event == 'end') {
      switch (this.sliderState) {
        case 'in':
          this.slideInEnded();
          break;
        case 'out':
          this.slideOutEnded();
          break;

        default:
          break;
      }
    }
    if (event == 'end' && this.sliderState == 'out') this.setDeactivable();
  }

  protected slideInEnded() {}
  protected slideOutEnded() {}

  protected _deactivate() {
    this.slideOut();
  }

  protected slideIn = () => {
    this.sliderState = 'in';
  };

  protected slideOut = () => {
    this.sliderState = 'out';
  };

  protected slideNone = () => {
    this.sliderState = 'none';
  };
}
