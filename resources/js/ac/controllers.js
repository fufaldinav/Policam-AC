axios.get(`/controllers/get_list`)
    .then(function (response) {
        for (let k in response.data) {
            Echo.private(`controller-events.${response.data[k].id}`)
                .listen('EventReceived', (e) => {
                    if (!events.includes(e.event)) {
                        return;
                    }
                    if (e.event == 16 || e.event == 17) {
                        setPersonInfo(e.card_id);
                    } else if (e.event == 2 || e.event == 3) {
                        if (!document.getElementById(`cards`).disabled) { //если меню неизвестных карт активно
                            let o = confirm(`Введен неизвестный ключ. Выбрать его в качестве нового ключа пользователя?`); //TODO перевод
                            if (o) {
                                getCards(e.card_id);
                            }
                        } else if (document.getElementById(`unknown_cards`).hidden) {
                            let o = confirm(`Введен неизвестный ключ. Добавить его текущему пользователю?`); //TODO перевод
                            if (o) {
                                saveCard(e.card_id);
                            }
                        }
                    }
                })
                .listen('ControllerConnected', (e) => {
                    SetControllerStatus(e.controller_id);
                });
        }
    })
    .catch(function (error) {
        console.log(error);
    });

window.SetControllerStatus = function (controller_id) {
    showNewEvent(`Контроллер <mark>ID: ${controller_id}</mark> вышел на связь.`)
}
