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

window.divisions = [];
window.persons = [];

class AcObject {
    constructor(data) {
        for (let k in data) {
            this[k] = data[k];
        }
    }
}

class Division extends AcObject {

}

class Person extends AcObject {

}

window.showPersons = function (div_id) {
    $(`.divisions`).hide();
    $(`#persons-div-${div_id}`).show();
}

window.showDivisions = function (div_id) {
    $(`.persons`).hide();
    $(`.divisions`).show();
}

//обновление информации пользователя в БД
window.updatePersonInfo = function () {
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
        axios.post(process.env.MIX_APP_URL + `/persons/save`,
            {
                cards: JSON.stringify(cards),
                divs: JSON.stringify(divs),
                person: JSON.stringify(person),
                photos: JSON.stringify(photos)
            })
            .then(function (response) {
                if (response.data > 0) {
                    alert(`Пользователь успешно сохранен`); //TODO перевод
                } else {
                    alert(`Не сохранено или данные совпали`); //TODO перевод
                }
                getCardsByPerson(person.id);
            })
            .catch(function (error) {
                console.log(error);
            });
    }
}

//удаление пользователя из БД
window.deletePerson = function () {
    if (!confirm(`Подтвердите удаление.`)) { //TODO перевод
        return;
    }
    axios.post(process.env.MIX_APP_URL + `/persons/delete`, {
            person_id: person.id
        })
        .then(function (response) {
            if (response.data > 0) {
                let currentElement = document.getElementById(`person${person.id}`);
                let parentElement = currentElement.parentElement; //родитель этого элемента
                currentElement.remove(); //удаляем элемент
                let lastElement = parentElement.lastElementChild;
                if (lastElement !== null) {
                    lastElement.classList.add(`tree-is-last`); //устанавливаем последний элемент в ветке
                }

                for (let k in person) {
                    let elem = document.getElementById(k);
                    elem.value = null;
                    elem.readOnly = true;
                    person[k] = null;
                }

                window.photos = [];
                document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/0.jpg)';
                document.getElementById(`photo`).hidden = true;
                document.getElementById(`photo`).onchange = function () {
                    return false;
                };
                document.getElementById(`photo_del`).onclick = function () {
                    return false;
                };
                document.getElementById(`photo_del`).hidden = true;

                window.cards = [];
                document.getElementById(`cards`).value = 0;
                document.getElementById(`person_cards`).innerHTML = ``; //очистка списка привязанных карт
                document.getElementById(`unknown_cards`).hidden = false; //отобразим меню с неизвестными картами
                document.getElementById(`cards`).disabled = true; //но запретим редактирование

                window.divs = [];

                document.getElementById(`save`).onclick = function () {
                    return false;
                };
                document.getElementById(`delete`).onclick = function () {
                    return false;
                };
                alert(`Пользователь успешно удален`); //TODO перевод
            } else {
                alert(`Пустой ответ от сервера`); //TODO перевод
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}

//получение данных пользователя из БД
window.getPersonInfo = function (person_id) {
    axios.get(process.env.MIX_APP_URL + `/persons/get/${person_id}`)
        .then(function (response) {
            let data = response.data;
            if (data) {
                for (let k in data.person) {
                    let elem = document.getElementById(k);
                    if (elem == null) {
                        continue;
                    }
                    person[k] = data.person[k];
                    elem.value = data.person[k];
                    elem.readOnly = false;
                }

                let photo_id = 0;
                window.photos = [];
                document.getElementById(`photo`).value = null;
                if (data.photos.length === 0) {
                    document.getElementById(`photo`).hidden = false;
                    document.getElementById(`photo_del`).hidden = true;
                    document.getElementById(`photo_del`).onclick = function () {
                        return false;
                    };
                } else {
                    photo_id = data.photos[0].id;
                    photos.unshift(photo_id);
                    document.getElementById(`photo`).hidden = true;
                    document.getElementById(`photo_del`).hidden = false;
                    document.getElementById(`photo_del`).onclick = deletePhoto;
                }
                document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/' + photo_id + '.jpg)';
                document.getElementById(`photo`).onchange = function () {
                    handleFiles(this.files);
                };

                window.divs = [];
                for (let k in data.divs) {
                    divs.push(data.divs[k].id);
                }

                document.getElementById(`save`).onclick = updatePersonInfo;
                document.getElementById(`delete`).onclick = deletePerson;
            } else {
                alert(`Пустой ответ от сервера`); //TODO перевод
            }
        })
        .catch(function (error) {
           console.log(error);
        });
}

//получение списка карт (брелоков) от сервера
window.getCardsByPerson = function (person_id) {
    axios.get(process.env.MIX_APP_URL + `/cards/get_list/${person_id}`)
        .then(function (response) {
            let data = response.data;
            window.cards = [];
            let person_cards = document.getElementById(`person_cards`);
            person_cards.innerHTML = ``;
            if (data.length > 0) {
                document.getElementById(`unknown_cards`).hidden = true; //спрячем неизвестные карты
                document.getElementById(`cards`).disabled = true; //отключим меню неизвеснтых карт
                for (let k in data) { //добавим каждую карту в список привязанных
                    person_cards.innerHTML += `<div id="card${data[k].id}">${data[k].wiegand} <button type="button" onclick="delCard(${data[k].id});">Отвязать</button><br /></div>`
                }
                let li = document.getElementById(`person${person.id}`); //добавим пользователю метку наличия ключей
                let a = li.querySelector(`.person`);
                a.classList.remove(`no-card`);
            } else {
                document.getElementById(`unknown_cards`).hidden = false; //отобразим неизвестные карты
                document.getElementById(`cards`).disabled = false; //включим меню неизвеснтых карт
                getCards();
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}

//добавление карты в БД
window.saveCard = function (card_id) {
    axios.post(process.env.MIX_APP_URL + `/cards/holder`, {
            card_id: card_id,
            person_id: person.id
        })
        .then(function (response) {
            if (response.data > 0) {
                getCardsByPerson(person.id);
                alert(`Ключ успешно добавлен`); //TODO перевод
            } else {
                alert(`Неизвестная ошибка`); //TODO перевод
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}

//удаление карты из БД
window.delCard = function (card_id) {
    if (!confirm(`Подтвердите удаление.`)) { //TODO перевод
        return;
    }
    axios.post(process.env.MIX_APP_URL + `/cards/holder`, {
            card_id: card_id,
            person_id: 0
        })
        .then(function (response) {
            if (response.data > 0) {
                let card = document.getElementById(`card${card_id}`);
                card.remove(); //удалим карту из списка привязанных
                let cardsHtml = document.getElementById(`person_cards`).innerHTML;
                cardsHtml = (cardsHtml.trim) ? cardsHtml.trim() : cardsHtml.replace(/^\s+/, ``);
                if (cardsHtml == ``) { //если список привязанных карт пуст, то отобразим и включим меню и запросим неизвеснтые карты
                    document.getElementById(`unknown_cards`).hidden = false;
                    document.getElementById(`cards`).disabled = false;
                    getCards();
                    let li = document.getElementById(`person${person.id}`); //удалим у пользователя метку наличия ключей
                    let a = li.querySelector(`.person`);
                    a.classList.add(`no-card`);
                }
                alert(`Ключ успешно отвязан`); //TODO перевод
            } else {
                alert(`Неизвестная ошибка`); //TODO перевод
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}
