class AcObject {
    constructor(data) {
        for (let k in data) {
            this[k] = data[k];
        }
    }
}

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
