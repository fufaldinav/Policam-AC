<script>
    `use strict`;

    //сохранить ошибку на сервере
    function sendError(message) {
        $.ajax({
            url: `{{ url('/') }}/util/save_js_errors`,
            type: `POST`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
            },
            data: {
                error: message
            }
        });
    }

    //получим список неизвестных карт (брелоков) из БД
    function getCards(id) {
        $.ajax({
            url: `{{ url('/') }}/cards/get_list`,
            type: `GET`,
            success: function(data) {
                if (data) {
                    let cards = document.getElementById(`cards`);
                    while (cards.length > 0) { //удалить все элементы из меню карт
                        cards.remove(cards.length - 1);
                    }
                    if (data.length == 0) { //если нет известных карт
                        addOption(cards, 0, `@lang('ac/common.missing')`);
                    } else { //иначе заполним меню картами
                        addOption(cards, 0, `@lang('ac/common.not_selected')`); //первый пункт
                        data.forEach(function(c) {
                            addOption(cards, c.id, c.wiegand);
                        });
                    }
                    if (id) { //если передавали id, то установим карту как текущую
                        cards.value = id;
                    }
                } else {
                    alert(`Пустой ответ от сервера`); //TODO перевод
                }
            },
            error: function() {
                alert(`Неизвестная ошибка`); //TODO перевод
            }
        });
    }

    //добавление опций в select
    function addOption(p, v, t) {
        let option = document.createElement(`option`);
        option.value = v;
        option.text = t;
        p.add(option);
    }

    //загрузка фото
    function handleFiles(files) {
        let formData = new FormData();
        formData.append(`file`, files[0]);
        $.ajax({
            url: `{{ url('/') }}/photos/save`,
            method: `POST`,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
            },
            cache: false,
            data: formData,
            success: function(data) {
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
            },
            error: function() {
                alert(`Неизвестная ошибка`); //TODO перевод
            }
        });
    }

    //удаление фото
    function deletePhoto() {
        if (!confirm(`Подтвердите удаление.`)) { //TODO перевод
            return;
        }
        $.ajax({
            url: `{{ url('/') }}/photos/delete`,
            type: `POST`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
            },
            data: {
              'photo_id': photos.shift()
            },
            success: function(res) {
                if (res) {
                    document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/0.jpg)';
                    document.getElementById(`photo_del`).hidden = true;
                    document.getElementById(`photo_del`).onclick = function() {
                        return false;
                    };
                    document.getElementById(`photo`).hidden = false;
                    document.getElementById(`photo`).value = null;
                } else {
                    alert(`Неизвестная ошибка`); //TODO перевод
                }
            },
            error: function() {
                alert(`Неизвестная ошибка`); //TODO перевод
            }
        });
    }
</script>
