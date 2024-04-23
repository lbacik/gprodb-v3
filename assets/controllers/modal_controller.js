import { Controller } from '@hotwired/stimulus'
import * as Turbo from '@hotwired/turbo'

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['dialog', 'dynamicContent']

    observer = null

    connect() {

        if (this.hasDynamicContentTarget) {
            // when the content changes, call this.open()
            this.observer = new MutationObserver(() => {
                const shouldOpen = this.dynamicContentTarget.innerHTML.trim().length > 0
                if (shouldOpen && !this.dialogTarget.open) {
                    this.open()
                } else if (!shouldOpen && this.dialogTarget.open) {
                    this.close()
                }
            })

            this.observer.observe(this.dynamicContentTarget, {
                childList: true,
                characterData: true,
                subtree: true
            })
        }

        document.addEventListener('turbo:before-fetch-response', (event) => {
            const fetchResponse = event.detail.fetchResponse;
            console.log('fetch response', event)
            if(fetchResponse.succeeded && fetchResponse.redirected) {
                console.log('redirect', fetchResponse.location)
                event.preventDefault()
                Turbo.visit(fetchResponse.location)
            }
        })
    }

    disconnect() {
        if (this.observer) {
            this.observer.disconnect();
        }
        if (this.dialogTarget.open) {
            this.close();
        }
    }

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
