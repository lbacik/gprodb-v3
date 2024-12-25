import { Controller } from '@hotwired/stimulus'
import { useTransition } from "stimulus-use"

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
  static values = {
    autoClose: Number,
  }

  static targets = ['timerbar']

  connect() {
     useTransition(this, {
            leaveActive: 'transition ease-in duration-200',
            leaveFrom: 'opacity-100',
            leaveTo: 'opacity-0',
            transitioned: true,
        });

    if (this.autoCloseValue) {
      setTimeout(() => this.close(), this.autoCloseValue)
    }

    if (this.hasTimerbarTarget) {
      setTimeout(() => this.timerbarTarget.style.width = 0, 10)
    }
  }

  close() {
    console.log('Closing')
    this.leave()
  }
}
