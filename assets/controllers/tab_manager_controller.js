import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {

    connect() {
        // const tab = this.getAnchor(document.URL) || 'base'
        this._makeVisible('base')
        this._makeButtonActive('base')
    }

    getAnchor(url) {
        return (url.split('#').length > 1) ? url.split('#')[1] : null;
    }

    changeTab(event) {
        if (event.target.dataset.tab) {
            this._makeVisible(event.target.dataset.tab)
            this._makeButtonActive(event.target.dataset.tab)
        }
    }

    _makeVisible(tab) {
        console.log('_makeVisible', tab)

        if (tab) {
            const tabElement = document.getElementById(tab)
            if (tabElement) {
                const currentActiveTab = document.querySelector('.tab.active')
                if (currentActiveTab) {
                    currentActiveTab.classList.remove('active')
                    currentActiveTab.classList.add('hidden')
                }
                tabElement.classList.remove('hidden')
                tabElement.classList.add('active')
            }
        }
    }

    _makeButtonActive(tab) {
        const button = document.querySelector(`[data-tab="${tab}"]`)
        const currentActiveButton = document.querySelector('.tab-button.active')

        if (currentActiveButton) {
            currentActiveButton.classList.remove('active')
        }

        if (button) {
            button.classList.add('active')
        }
    }
}
