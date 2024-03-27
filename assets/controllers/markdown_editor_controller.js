import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['editor']

    connect() {
        this.editorTargets.forEach((editor) => {
            new SimpleMDE({
                element: editor,
                toolbar: [
                    "bold", "italic", "heading", "|",
                    "quote", "unordered-list", "ordered-list", "|",
                    "link", "table", "|",
                    "preview", "guide"
                ]
            })
        })
    }
}
