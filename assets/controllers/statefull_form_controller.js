import { ApplicationController, useDebounce } from 'stimulus-use'
import FormDataStorage from '../services/form_data_storage.js'

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends ApplicationController {
  static debounces = ['saveState']

  markdownEditorController = null

  textAreas = [
    'about[columnLeft]',
    'about[columnRight]',
  ]

  connect() {
    useDebounce(this, { wait: 500 })
    this._updateFormWithLocalFormData()
  }

  storageSave() {
    const form = new FormData(this.element)

    for (let [key, value] of form.entries()) {
      if (key.endsWith('[_token]')) {
        continue
      }

      if (this.textAreas.includes(key)) {
        value = this._getValueForTextarea(key)
      }

      if (this.element.elements[key].type === 'checkbox') {
        if (this.element.elements[key].checked) {
          value = true
        } else {
          value = false
        }
      }

      this._localFormDataRepository().set(key, value)
    }

  }

  saveState(event) {
    if (event.target.type === 'checkbox') {
      this._localFormDataRepository().set(event.target.name, event.target.checked)
    } else if (event.target.type === 'textarea') {
      this.textAreas.forEach((key) => {
        if (this.element.elements[key]) {
          this._localFormDataRepository().set(key, this._getValueForTextarea(key))
        }
      })
    } else {
      this._localFormDataRepository().set(event.target.name, event.target.value)
    }

    console.log('saved data', this._localFormDataRepository().data)
  }


  cancel() {
    this._localFormDataRepository().clear()
  }

  submit(event) {
    event.preventDefault()

    this.storageSave()
    console.log('submit', this.element.method, this.element.action, this._localFormDataRepository().data)

    // localStorage.clear()
  }

  _getValueForTextarea(key) {
    return this._markdownController().getDataForElementName(key)
  }

  _setValueForTextarea(key, value) {
    this._markdownController().setDataForElementName(key, value)
  }

  _markdownController() {
    if (this.markdownEditorController === null) {
      this.markdownEditorController = this.application.getControllerForElementAndIdentifier(
        document.getElementById('markdown-editor'),
        'markdown-editor'
      )
    }

    return this.markdownEditorController
  }

  _localFormDataRepository() {
    if (!window.formDataStorage) {
      window.formDataStorage = new FormDataStorage()
    }

    return window.formDataStorage
  }

  _updateFormWithLocalFormData() {

    console.log('update form with local data', this._localFormDataRepository().data)

    for (const [key, value] of Object.entries(this._localFormDataRepository().data)) {
      if (this.element.elements[key]) {

        if (this.textAreas.includes(key)) {
          this._setValueForTextarea(key, value)
        } else if (this.element.elements[key].type === 'checkbox') {
          this.element.elements[key].checked = value
        } else {
          this.element.elements[key].value = value
        }
      }
    }
  }
}
