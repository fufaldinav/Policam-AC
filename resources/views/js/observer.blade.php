<script>
    `use strict`;

    let events = [16, 17]; //где 16, 17 - вход/выход состоялся

    //получение данных пользователя из БД
    function setPersonInfo(card_id) {
        axios.get(`{{ url('/') }}/persons/get_by_card/${card_id}`)
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

    function getDivisions() {
        axios.get(`{{ url('/') }}/divisions/get_list`)
            .then(function (response) {
                let data = response.data;
                if (data.length > 0) {
                    let divisions = ``;
                    data.forEach(function (div) {
                        divisions += `<div id="div${div.id}" class="menu-item" onclick="getPersons(${div.id});">${div.name}</div>`;
                    });
                    let menu = document.getElementById(`menu`);
                    menu.innerHTML = divisions;
                } else {
                    alert(`Пустой ответ от сервера`); //TODO перевод
                }
            })
            .catch(function (error) {
                console.log(error);
            })
            .then(function () {
                // always executed
            });
    }

    function getPersons(div_id) {
        $.ajax({
            url: `{{ url('/') }}/persons/get_list/${div_id}`,
            type: `GET`,
            success: function (data) {
                let persons = `<div id="menu-button-back" class="menu-item" onclick="getDivisions();">Назад</div>`; //TODO перевод
                if (data.length > 0) {
                    data.forEach(function (person) {
                        persons += `<div id="person${person.id}" class="menu-item" onclick="openEntraceOptions(${person.id}, ${div_id});">${person.f} ${person.i}</div>`;
                    });
                }
                document.getElementById(`menu`).innerHTML = persons;
            },
            error: function () {
                alert(`Неизвестная ошибка`); //TODO перевод
            }
        });
    }

    function openEntraceOptions(person_id, div_id) {
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

    function sendInfo(type, person_id) {
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
        $.ajax({
            url: `{{ url('/') }}/util/card_problem`,
            type: `POST`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
            },
            data: {
                type: type,
                person_id: person_id
            },
            success: function (res) {
                if (res) {
                    alert(res);
                } else {
                    alert(`Пустой ответ от сервера`); //TODO перевод
                }
            },
            error: function () {
                alert(`Неизвестная ошибка`); //TODO перевод
            }
        });
    }
</script>
