`use strict`;

let data = {
    "type": "Z5RWEB",
    "sn": 50001,
    "messages": [
        {
            "id": 1010,
            "operation": "events",
            "events": [
                {
                    "event": 4,
                    "card": "00B5009EC1A8",
                    "time": "2015-06-25 16:36:01",
                    "flag": 0
                }
            ]
        }
    ]
};

// отправка ID на сервер
function sendTest() {
    console.log(`Отправка на сервер...`); //TODO перевод
    $.ajax({
        url: `/laravel/server`,
        type: `POST`,
        data: JSON.stringify(data),
        success: function (res) {
            console.log(res);
        },
        error: function () {
            console.warn(`Неизвестная ошибка`); //TODO перевод
        }
    });
}
