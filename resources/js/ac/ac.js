window.AcClass = function (data) {
    this.form = document.forms.namedItem(`form-person`);
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

        let menu = document.getElementById(`ac-list-group`);
        let list = document.createElement(`div`);
        list.classList.add(`list-group`, `list-group-flush`);

        for (let k in this.divisions) {
            let div = this.divisions[k];
            let count = div.persons().length;

            let button = document.createElement(`button`);
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

            let badge = document.createElement(`span`);
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

        let menu = document.getElementById(`ac-list-group`);
        let list = document.createElement(`div`);
        list.classList.add(`list-group`, `list-group-flush`);

        let buttonBack = document.createElement(`button`);
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
        let person_id = _getIdFromElement(element);
        let person = this.persons[person_id];
        _enableForm();
        for (let i = 0; i < this.form.length; i++) {
            if (person.hasOwnProperty(this.form[i].id)) {
                this.form[i].value = person[this.form[i].id];
            }
        }
    }

    this.newPersonInForm = function (element) {
        let division_id = _getIdFromElement(element);
        _enableForm();
        this.divisions[division_id].persons().push(0);
        let person = this.persons[0] = new Person();
        person.divisions().push(parseInt(division_id));
    }

    let _getIdFromElement = function (element) {
        return element.id.split(`-`).pop();
    }.bind(this);

    let _enableForm = function () {
        for (let i = 0; i < this.form.length; i++) {
            this.form[i].value = null;
            if (this.form[i].disabled) {
                this.form[i].disabled = false;
            }
        }
    }.bind(this);

    let _disableForm = function () {
        for (let i = 0; i < this.form.length; i++) {
            this.form[i].value = null;
            if (!this.form[i].disabled) {
                this.form[i].disabled = true;
            }
        }
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
