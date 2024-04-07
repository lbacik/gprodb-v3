

class FormDataStorage
{
  constructor()
    {
        this.data = {};
    }

    set(key, value)
    {
        this.data[key] = value;
    }

    get(key)
    {
        return this.data[key];
    }

    clear()
    {
        this.data = {};
    }
}

export default FormDataStorage;
