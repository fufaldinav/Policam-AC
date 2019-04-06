let cards = [];

//получим список неизвестных карт (брелоков) из БД
window.getCards = function (id) {
    axios.get(process.env.MIX_APP_URL + `/cards/get_list`)
        .then(function (response) {
            let data = response.data;
            if (data) {
                let cards_selector = document.getElementById(`cards`);
                while (cards_selector.length > 0) { //удалить все элементы из меню карт
                    cards_selector.remove(cards_selector.length - 1);
                }
                if (data.length == 0) { //если нет известных карт
                    addOption(cards_selector, 0, trans('ac.missing'));
                } else { //иначе заполним меню картами
                    addOption(cards_selector, 0, trans('ac.not_selected')); //первый пункт
                    data.forEach(function (c) {
                        addOption(cards_selector, c.id, c.wiegand);
                    });
                }
                if (id) { //если передавали id, то установим карту как текущую
                    cards_selector.value = id;
                }
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
            cards = [];
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
