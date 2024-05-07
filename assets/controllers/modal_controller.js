import { Controller } from '@hotwired/stimulus'
import * as Turbo from '@hotwired/turbo'

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['dialog', 'dynamicContent']

    // modal = null
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

        this.bindBeforeFetchResponse = this.beforeFetchResponse.bind(this)
        document.addEventListener('turbo:before-fetch-response', this.bindBeforeFetchResponse)
    }

    disconnect() {
        document.removeEventListener('turbo:before-fetch-response', this.bindBeforeFetchResponse)

        if (this.observer) {
            this.observer.disconnect();
        }
        if (this.dialogTarget.open) {
            this.close();
        }
    }

    beforeFetchResponse(event) {
        if (!this.dialogTarget.open) {
            return;
        }

        const fetchResponse = event.detail.fetchResponse;
        if (fetchResponse.succeeded && fetchResponse.redirected) {
            console.log('redirect', fetchResponse.location)
            event.preventDefault();
            Turbo.visit(fetchResponse.location);
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
