<script>
    `use strict`;

    let events = [2, 3]; //где 2,3 - события запрещенного входа/выхода
    let person = {
        'f': null,
        'i': null,
        'o': null,
        'birthday': null,
        'address': null,
        'phone': null
    };

    let cards = [],
        divs = [],
        photos = [];

    function setDiv(id) {
        let index = divs.indexOf(id);
        if (index === -1) {
            divs.push(id);
            document.getElementById(`div${id}`).classList.add(`checked`);
        } else {
            divs.splice(index, 1);
            document.getElementById(`div${id}`).classList.remove(`checked`);
        }
    }

    function savePersonInfo() {
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
            $.ajax({
                url: `{{ url('/') }}/persons/save`,
                type: `POST`,
                data: {
                    '_token': $('meta[name=csrf-token]').attr('content'),
                    cards: JSON.stringify(cards),
                    divs: JSON.stringify(divs),
                    person: JSON.stringify(person),
                    photos: JSON.stringify(photos)
                },
                success: function(person_id) {
                    for (let k in person) {
                        person[k] = null;
                    }
                    cards = [];
                    alert(`Пользователь №${person_id} успешно сохранен`); //TODO перевод
                    clearPersonInfo();
                },
                error: function() {
                    alert(`Неизвестная ошибка`); //TODO перевод
                }
            });
        }
    }

    function clearPersonInfo() {
        for (let k in person) {
            document.getElementById(k).value = null;
        }
        photos = [];
        document.getElementById(`cards`).value = 0;
        document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/0.jpg)';
        document.getElementById(`photo_del`).hidden = true;
        document.getElementById(`photo_del`).onclick = function() {
            return false;
        };
    }

    function checkData(e) {
        e.classList.remove(`no-data`);
    }
</script>
