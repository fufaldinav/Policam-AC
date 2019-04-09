window.AcClass = function (data) {
    let d = document;
    let selectedPerson = null;
    let selectedDivision = null;
    let form = d.forms.namedItem(`form-person`);
    let menu = d.getElementById(`ac-list-group`);
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

    this.listGroupDivisions = function (element) {
        if (element !== undefined) {
            let division_id = _getIdFromElement(element);
            delete this.divisions[division_id].deletePerson(0);
            delete this.persons[0];
        }

        _disableForm();

        let list = d.createElement(`div`);
        list.classList.add(`list-group`, `list-group-flush`);

        for (let k in this.divisions) {
            let div = this.divisions[k];
            let count = div.persons().length;

            let button = d.createElement(`button`);
            button.type = `button`;
            button.classList.add(`list-group-item`, `list-group-item-action`, `d-flex`, `justify-content-between`, `align-items-center`);
            button.id = `ac-button-division-${k}`;
            button.onclick = function () {
                window.Ac.listGroupPersons(this);
            };
            button.textContent = div.name;
            if (count === 0) {
                button.disabled = true;
            }

            let badge = d.createElement(`span`);
            badge.classList.add(`badge`, `badge-primary`, `badge-pill`);
            badge.textContent = count;

            button.appendChild(badge);
            list.appendChild(button);
        }

        menu.innerHTML = ``;
        menu.appendChild(list);
    }

    this.listGroupPersons = function (element) {
        let division_id = _getIdFromElement(element);

        let list = d.createElement(`div`);
        list.classList.add(`list-group`, `list-group-flush`);

        let buttonBack = d.createElement(`button`);
        buttonBack.type = `button`;
        buttonBack.classList.add(`list-group-item`, `list-group-item-info`);
        buttonBack.id = `ac-button-back-${division_id}`;
        buttonBack.onclick = function () {
            window.Ac.listGroupDivisions(this);
        };
        buttonBack.textContent = `Back`;

        list.appendChild(buttonBack);

        let buttonAdd = buttonBack.cloneNode(true);
        buttonAdd.classList.remove(`list-group-item-info`);
        buttonAdd.classList.add(`list-group-item-success`);
        buttonAdd.id = `ac-button-add-${division_id}`;
        buttonAdd.onclick = function () {
            window.Ac.newPersonInForm(this);
        };
        buttonAdd.textContent = `Add`;

        list.appendChild(buttonAdd);

        let persons = this.divisions[division_id].persons();
        for (let id of persons) {
            let person = this.persons[id];

            let button = buttonBack.cloneNode(true);
            button.classList.remove(`list-group-item-info`);
            button.classList.add(`list-group-item-action`);
            button.id = `ac-button-person-${id}`;
            button.onclick = function () {
                window.Ac.showPersonInForm(this);
            };
            button.textContent = `${person.f} ${person.i}`;

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
        selectedPerson.save();
        console.log(`save`);
    }

    this.clearPerson = function () {
        _disableForm();
        console.log(`clear`);
    }

    this.updatePerson = function () {
        for (let i = 0; i < form.length; i++) {
            if (selectedPerson.hasOwnProperty(form[i].id)) {
                selectedPerson[form[i].id] = form[i].value;
            }
        }
        selectedPerson.update();
        console.log(`update`);
    }

    this.deletePerson = function () {
        selectedPerson.delete();
        console.log(`delete`);
    }

    let _getIdFromElement = function (element) {
        return element.id.split(`-`).pop();
    }.bind(this);

    let _enableForm = function () {
        for (let i = 0; i < form.length; i++) {
            form[i].value = null;
            if (form[i].disabled) {
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
}

let Ac = window.Ac = new AcClass(window.AcData);
delete window.AcData;
Ac.listGroupDivisions();

window.showNewEvent = function (event) {
    $(`.events`).append(`<p class="mb-1"><small>${event}</small></p>`);
}

window.openEntranceOptions = function (person_id, div_id) {
    let options = ``;
    if (div_id === undefined) {
        options += `<div id="menu-button-back" class="menu-item" onclick="getDivisions();">Назад</div>`; //TODO перевод
    } else {
        options += `<div id="menu-button-back" class="menu-item" onclick="getPersons(${div_id});">Назад</div>`; //TODO перевод
    }
    options += `<div id="menu-button-forgot" class="menu-item" onclick="sendInfo(1, ${person_id})">Забыл</div>`; //TODO перевод
    options += `<div id="menu-button-lost" class="menu-item" onclick="sendInfo(2, ${person_id})">Потерял</div>`; //TODO перевод
    options += `<div id="menu-button-broke" class="menu-item" onclick="sendInfo(3, ${person_id})">Сломал</div>`; //TODO перевод
    let menu = document.getElementById(`menu`);
    menu.innerHTML = options;
}

window.sendInfo = function (type, person_id) {
    let msg;
    switch (type) {
        case 1:
            msg = `На сервер будет отправлено уведомление.`; //TODO перевод
            if (!confirm(msg)) return;
            break;
        case 2:
            msg = `Карта будет удалена, а на сервер будет отправлено уведомление.`; //TODO перевод
            if (!confirm(msg)) return;
            break;
        case 3:
            msg = `Карта будет удалена, а на сервер будет отправлено уведомление.`; //TODO перевод
            if (!confirm(msg)) return;
            break;
    }
    axios.post(`/util/card_problem`, {
        type: type,
        person_id: person_id
    })
        .then(function (response) {
            if (response.data) {
                alert(response.data);
            } else {
                alert(`Пустой ответ от сервера`); //TODO перевод
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}

//сохранить ошибку на сервере
window.sendError = function (message) {
    axios.post(`/util/save_errors`, {
        error: message
    })
        .catch(function (error) {
            console.log(error);
        });
}

//добавление опций в select
function addOption(elem, value, text) {
    let option = document.createElement(`option`);
    option.value = value;
    option.text = text;
    elem.add(option);
}

let trans = function (key, replace = {}) {
    let translation = key.split('.').reduce((t, i) => t[i] || null, window.translations);

    for (let placeholder in replace) {
        translation = translation.replace(`:${placeholder}`, replace[placeholder]);
    }

    return translation;
}

let trans_choice = function (key, count = 1, replace = {}) {
    let translation = key.split('.').reduce((t, i) => t[i] || null, window.translations).split('|');

    translation = count > 1 ? translation[1] : translation[0];

    for (let placeholder in replace) {
        translation = translation.replace(`:${placeholder}`, replace[placeholder]);
    }

    return translation;
}
