import {Division, Person} from "./classes";

export default function (data) {
    if (data === undefined) {
        return;
    }

    let d = document;
    let selectedPerson = null;
    let selectedDivision = null;
    let form = d.forms.namedItem(`form-person`);
    let menu = d.getElementById(`ac-menu-left`);
    let buttonSave = d.getElementById(`ac-button-save`);
    let buttonDelete = d.getElementById(`ac-button-delete`);

    this.divisions = [];
    this.persons = [];

    if (data['divisions'] !== undefined) {
        for (let k in data['divisions']) {
            this.divisions[k] = new Division(data['divisions'][k]);
        }
    }
    if (data['persons'] !== undefined) {
        for (let k in data['persons']) {
            this.persons[k] = new Person(data['persons'][k]);
        }
    }

    this.alert = function (text, type) {
        if (type === undefined) {
            type = `info`;
        }

        let alert = d.createElement(`div`);
        alert.classList.add(`alert`, `alert-${type}`, `alert-dismissible`, `fade`, `show`, `ac-alert`);
        alert.role = `alert`;
        alert.textContent = text;

        d.body.appendChild(alert);

        function close() {
            $(alert).alert(`close`);
        }

        setTimeout(close, 5000);
    }

    this.listGroupDivisions = function (element) {
        selectedDivision = null;

        if (element !== undefined) {
            let division_id = _getIdFromElement(element);
            delete this.divisions[division_id].deletePerson(0);
            delete this.persons[0];
        }

        _disableForm();

        let list = d.createElement(`div`);
        list.id = `ac-list-divisions`;
        list.classList.add(`list-group`, `list-group-flush`);

        for (let k in this.divisions) {
            let div = this.divisions[k];

            let button = _createButton(div);

            list.appendChild(button);
        }

        menu.innerHTML = ``;
        menu.appendChild(list);
    }

    this.listGroupPersons = function (element) {
        let division_id = _getIdFromElement(element);
        selectedDivision = Ac.divisions[division_id];

        let list = d.createElement(`div`);
        list.id = `ac-list-persons`;
        list.classList.add(`list-group`, `list-group-flush`);

        let buttonBack = _createButton(`back`);

        list.appendChild(buttonBack);

        let buttonAdd = _createButton(`add`);

        list.appendChild(buttonAdd);

        let persons = this.divisions[division_id].persons();
        for (let id of persons) {
            let person = this.persons[id];

            let button = _createButton(person);

            list.appendChild(button);
        }

        menu.innerHTML = ``;
        menu.appendChild(list);
    }

    this.showPersonInForm = function (element) {
        _enableForm();

        buttonSave.onclick = function () {
            window.Ac.updatePerson();
        }
        buttonSave.textContent = `Update`;

        buttonDelete.classList.remove(`btn-secondary`);
        buttonDelete.classList.add(`btn-danger`);
        buttonDelete.onclick = function () {
            window.Ac.deletePerson();
        }
        buttonDelete.textContent = `Delete`;

        let person_id = _getIdFromElement(element);
        selectedPerson = this.persons[person_id];
        for (let i = 0; i < form.length; i++) {
            if (selectedPerson.hasOwnProperty(form[i].id)) {
                form[i].value = selectedPerson[form[i].id];
            }
        }
    }

    this.newPersonInForm = function (element) {
        _enableForm();

        buttonSave.onclick = function () {
            window.Ac.savePerson();
        }
        buttonSave.textContent = `Save`;

        buttonDelete.classList.remove(`btn-danger`);
        buttonDelete.classList.add(`btn-secondary`);
        buttonDelete.onclick = function () {
            window.Ac.clearPerson();
        }
        buttonDelete.textContent = `Cancel`;

        let division_id = _getIdFromElement(element);

        this.divisions[division_id].persons().push(0);
        selectedPerson = this.persons[0] = new Person();
        selectedPerson.divisions().push(parseInt(division_id));
    }

    this.savePerson = function () {
        for (let i = 0; i < form.length; i++) {
            if (selectedPerson.hasOwnProperty(form[i].id)) {
                selectedPerson[form[i].id] = form[i].value;
            }
        }
        selectedPerson.save().then(data => {
            delete this.persons[0];
            selectedDivision.deletePerson(0);

            let person = this.persons[data.id] = new Person(data);

            for (let k in data.divisions) {
                let div = data.divisions[k];
                this.divisions[div.id].addPerson(person.id);
            }

            let list = d.getElementById(`ac-list-persons`);

            let button = _createButton(person);

            list.appendChild(button);

            this.alert(`${person.f} ${person.i} сохранен успешно`, `success`);
        });
    }

    this.clearPerson = function () {
        _disableForm();
        this.alert(`Форма очищена`);
    }

    this.updatePerson = function () {
        for (let i = 0; i < form.length; i++) {
            if (selectedPerson.hasOwnProperty(form[i].id)) {
                selectedPerson[form[i].id] = form[i].value;
            }
        }
        selectedPerson.update().then(person => {
            for (let k in person.divisions) {
                let div = person.divisions[k];
                this.divisions[div.id].addPerson(person.id);
            }

            let button = d.getElementById(`ac-button-person-${person.id}`);
            button.textContent = `${person.f} ${person.i}`;

            this.alert(`${person.f} ${person.i} обновлен успешно`, `success`);
        });
    }

    this.deletePerson = function () {
        selectedPerson.delete().then(id => {
            let person = this.persons[id];

            delete this.persons[id];
            selectedDivision.deletePerson(id);

            let button = d.getElementById(`ac-button-person-${id}`);
            button.parentElement.removeChild(button);

            _disableForm();
            this.alert(`${person.f} ${person.i} удален успешно`);
        });
    }

    let _getIdFromElement = function (element) {
        return element.id.split(`-`).pop();
    }.bind(this);

    let _enableForm = function () {
        for (let i = 0; i < form.length; i++) {
            form[i].value = null;
            if (form[i].disabled && form[i].id !== `id`) {
                form[i].disabled = false;
            }
        }
    }.bind(this);

    let _disableForm = function () {
        for (let i = 0; i < form.length; i++) {
            form[i].value = null;
            if (!form[i].disabled) {
                form[i].disabled = true;
            }
        }
        selectedPerson = null;
    }.bind(this);

    let _createButton = function (object) {
        let button = d.createElement(`button`);
        button.type = `button`;
        button.classList.add(`list-group-item`);

        if (object === `add`) {
            button.classList.add(`list-group-item-success`);
            button.id = `ac-button-add-${selectedDivision.id}`;
            button.onclick = function () {
                window.Ac.newPersonInForm(this);
            };
            button.textContent = `Add`;
        } else if (object === `back`) {
            button.classList.add(`list-group-item-info`);
            button.id = `ac-button-back-${selectedDivision.id}`;
            button.onclick = function () {
                window.Ac.listGroupDivisions(this);
            };
            button.textContent = `Back`;
        } else if (object.constructor.name === `Division`) {
            button.classList.add(`list-group-item-action`, `d-flex`, `justify-content-between`, `align-items-center`);
            button.id = `ac-button-division-${object.id}`;
            button.onclick = function () {
                window.Ac.listGroupPersons(this);
            };
            button.textContent = object.name;

            let count = object.persons().length;

            if (count === 0) {
                button.disabled = true;
            }

            let badge = d.createElement(`span`);
            badge.classList.add(`badge`, `badge-primary`, `badge-pill`);
            badge.textContent = `${count}`;

            button.appendChild(badge);
        } else if (object.constructor.name === `Person`) {
            button.classList.add(`list-group-item-action`);
            button.id = `ac-button-person-${object.id}`;
            button.onclick = function () {
                window.Ac.showPersonInForm(this);
            };
            button.textContent = `${object.f} ${object.i}`;
        }

        return button;
    }.bind(this);
}

window.showNewEvent = function (event) {
    $(`.events`).append(`<p class="mb-1"><small>${event}</small></p>`);
}

let trans = function (key, replace = {}) {
    let translation = key.split('.').reduce((t, i) => t[i] || null, window.translations);

    for (let placeholder in replace) {
        translation = translation.replace(`:${placeholder}`, replace[placeholder]);
    }

    return translation;
}

let transChoice = function (key, count = 1, replace = {}) {
    let translation = key.split('.').reduce((t, i) => t[i] || null, window.translations).split('|');

    translation = count > 1 ? translation[1] : translation[0];

    for (let placeholder in replace) {
        translation = translation.replace(`:${placeholder}`, replace[placeholder]);
    }

    return translation;
}
