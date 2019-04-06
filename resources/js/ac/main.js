//сохранить ошибку на сервере
window.sendError = function (message) {
    axios.post(process.env.MIX_APP_URL + `/util/save_errors`, {
            error: message
        })
        .catch(function (error) {
            console.log(error);
        });
}

//получим список неизвестных карт (брелоков) из БД
window.getCards = function (id) {
    axios.get(process.env.MIX_APP_URL + `/cards/get_list`)
        .then(function (response) {
            let data = response.data;
            if (data) {
                let cards = document.getElementById(`cards`);
                while (cards.length > 0) { //удалить все элементы из меню карт
                    cards.remove(cards.length - 1);
                }
                if (data.length == 0) { //если нет известных карт
                    addOption(cards, 0, trans('ac.missing'));
                } else { //иначе заполним меню картами
                    addOption(cards, 0, trans('ac.not_selected')); //первый пункт
                    data.forEach(function (c) {
                        addOption(cards, c.id, c.wiegand);
                    });
                }
                if (id) { //если передавали id, то установим карту как текущую
                    cards.value = id;
                }
            } else {
                alert(`Пустой ответ от сервера`); //TODO перевод
            }
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

//загрузка фото
window.handleFiles = function (files) {
    let formData = new FormData();
    formData.append(`file`, files[0]);
    axios({
            method: `post`,
            url: process.env.MIX_APP_URL + `/photos/save`,
            data: formData,
            config: { headers: {'Content-Type': 'multipart/form-data' }}
        })
        .then(function (response) {
            let data = response.data;
            if (data) {
                if (data.error === ``) {
                    document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/' + data.id + '.jpg)';
                    photos.unshift(data.id);
                    document.getElementById(`photo`).hidden = true;
                    document.getElementById(`photo_del`).hidden = false;
                    document.getElementById(`photo_del`).onclick = deletePhoto;
                } else {
                    document.getElementById(`photo`).value = null;
                    alert(data.error);
                }
            } else {
                alert(`Неизвестная ошибка`); //TODO перевод
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}

//удаление фото
window.deletePhoto = function () {
    if (!confirm(`Подтвердите удаление.`)) { //TODO перевод
        return;
    }
    axios.post(process.env.MIX_APP_URL + `/photos/delete`, {
            photo_id: photos.shift()
        })
        .then(function (response) {
            if (response.data) {
                document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/0.jpg)';
                document.getElementById(`photo_del`).hidden = true;
                document.getElementById(`photo_del`).onclick = function () {
                    return false;
                };
                document.getElementById(`photo`).hidden = false;
                document.getElementById(`photo`).value = null;
            } else {
                alert(`Неизвестная ошибка`); //TODO перевод
            }
        })
        .catch(function (error) {
            console.log(error);
        });
}
