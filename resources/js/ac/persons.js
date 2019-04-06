let person = {
    'f': null,
    'i': null,
    'o': null,
    'birthday': null,
    'address': null,
    'phone': null
};

let persons = [];

window.setPersonInfo = function (card_id) {
    axios.get(process.env.MIX_APP_URL + `/persons/get_by_card/${card_id}`)
        .then(function (response) {
            if (response.data) {
                let data = response.data;
                for (let k in data.person) {
                    if (k === `divs`) {
                        data.person[k].forEach(function (div) {
                            document.getElementById(k).innerHTML = div.name; //TODO списком
                        });
                    } else {
                        let elem = document.getElementById(k);
                        if (elem == null) {
                            continue;
                        }
                        elem.value = data.person[k];
                    }
                }

                let photo_id = 0;
                let photo = document.getElementById(`photo_bg`);
                if (data.photos.length > 0) {
                    photo_id = data.photos[0].id;
                }
                photo.style.backgroundImage = 'url(/img/ac/s/' + photo_id + '.jpg)';
            } else {
                console.log(`Пустой ответ от сервера`); //TODO перевод
            }
        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
            // always executed
        });
}

window.getPersons = function (div_id) {
    axios.get(process.env.MIX_APP_URL + `/persons/get_list/${div_id}`)
        .then(function (response) {
            let data = response.data;
            let persons = `<div id="menu-button-back" class="menu-item" onclick="getDivisions();">Назад</div>`; //TODO перевод
            if (data.length > 0) {
                data.forEach(function (person) {
                    persons += `<div id="person${person.id}" class="menu-item" onclick="openEntranceOptions(${person.id}, ${div_id});">${person.f} ${person.i}</div>`;
                });
            }
            document.getElementById(`menu`).innerHTML = persons;
        })
        .catch(function (error) {
            console.log(error);
        });
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

                photos = [];
                document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/0.jpg)';
                document.getElementById(`photo`).hidden = true;
                document.getElementById(`photo`).onchange = function () {
                    return false;
                };
                document.getElementById(`photo_del`).onclick = function () {
                    return false;
                };
                document.getElementById(`photo_del`).hidden = true;

                cards = [];
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
