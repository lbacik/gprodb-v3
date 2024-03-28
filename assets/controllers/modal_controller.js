import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['dialog']

    open() {
        this.dialogTarget.showModal()
        document.body.classList.add('overflow-hidden')
    }

    close() {
        if (this.hasDialogTarget) {
            this.dialogTarget.close()
            document.body.classList.remove('overflow-hidden')
        }
    }

    clickOutside(event) {
        if (event.target === this.dialogTarget) {
            this.dialogTarget.close()
        }
    }
}
