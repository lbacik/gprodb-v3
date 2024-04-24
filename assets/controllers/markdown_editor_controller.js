import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['editor']

    editors = []

    connect() {
        this.editorTargets.forEach((editor) => {
            const editorInstance = new SimpleMDE({
                spellChecker: false,
                element: editor,
                toolbar: [
                    "bold", "italic", "heading", "|",
                    "quote", "unordered-list", "ordered-list", "|",
                    "link", "table", "|",
                    "preview", "guide"
                ]
              })
            this.editors.push(editorInstance)
        })
    }

    getDataForElementName(elementName) {

        for (let editor of this.editors) {
            if (editor.options.element.name === elementName) {
                return editor.value()
            }
        }

        return null
    }

    setDataForElementName(elementName, data) {
        for (let editor of this.editors) {
            if (editor.options.element.name === elementName) {
                editor.value(data)
                break
            }
        }
    }

    update() {
        this.editors.forEach((editor) => editor.codemirror.refresh())
    }
}
