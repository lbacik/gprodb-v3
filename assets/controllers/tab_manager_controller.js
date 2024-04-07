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
    }

    getAnchor(url) {
        return (url.split('#').length > 1) ? url.split('#')[1] : null;
    }

    changeTab(event) {
        console.log('changeTab', event.target)

        if (event.target.dataset.tab) {
            this._makeVisible(event.target.dataset.tab)
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
}
