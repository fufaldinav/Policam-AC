window.events = [2, 3]; //где 2,3 - события запрещенного входа/выхода
window.person = {
    'f': null,
    'i': null,
    'o': null,
    'birthday': null,
    'address': null,
    'phone': null
};

window.cards = [];
window.divs = [];
window.photos = [];

window.setDiv = function (id) {
    let index = window.divs.indexOf(id);
    if (index === -1) {
        window.divs.push(id);
        document.getElementById(`div${id}`).classList.add(`checked`);
    } else {
        window.divs.splice(index, 1);
        document.getElementById(`div${id}`).classList.remove(`checked`);
    }
}

window.savePersonInfo = function () {
    let checkValidity = true;

    for (let k in person) {
        let elem = document.getElementById(k);
        if (elem.required && elem.value === ``) {
            elem.classList.add(`no-data`);
            checkValidity = false;
        }
        if (elem.value) {
            person[k] = elem.value;
        } else {
            person[k] = null;
        }
    }

    let elem = document.getElementById(`cards`);
    if (elem.value > 0) {
        cards.push(elem.value);
    }

    if (!checkValidity) {
        alert(`Введены не все данные`); //TODO перевод
    } else {
        axios.post(process.env.MIX_APP_URL + `/persons/save`, {
                cards: JSON.stringify(cards),
                divs: JSON.stringify(divs),
                person: JSON.stringify(person),
                photos: JSON.stringify(photos)
            })
            .then(function (response) {
                let person_id = response.data;
                for (let k in person) {
                    person[k] = null;
                }
                cards = [];
                alert(`Пользователь №${person_id} успешно сохранен`); //TODO перевод
                clearPersonInfo();
            })
            .catch(function (error) {
                console.log(error);
            });
    }
}

window.clearPersonInfo = function () {
    for (let k in person) {
        document.getElementById(k).value = null;
    }
    photos = [];
    document.getElementById(`cards`).value = 0;
    document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/0.jpg)';
    document.getElementById(`photo_del`).hidden = true;
    document.getElementById(`photo_del`).onclick = function () {
        return false;
    };
}

window.checkData = function (e) {
    e.classList.remove(`no-data`);
}
