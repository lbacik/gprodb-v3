import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['menu']

    menuVisible = false;

     toggle() {
       this.menuVisible = !this.menuVisible;
       this.menuTarget.classList.toggle('hidden', !this.menuVisible);
     }
}
