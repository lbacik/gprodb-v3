import {Controller} from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static targets = ['object']

  objectVisible = false;

  toggle() {
    this.objectVisible = !this.objectVisible;
    this.objectTarget.classList.toggle('hidden', !this.objectVisible);
  }

  close(event) {
    if (this.objectVisible && event.target === this.objectTarget) {
      this.objectVisible = false;
      this.objectTarget.classList.add('hidden');
    }
  }
}
