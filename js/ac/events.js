let time;

document.addEventListener("DOMContentLoaded", function() {
	time = getServerTime();
	getNewMsgs(events, time);
});

//получение времени от сервера
function getServerTime() {
	$.ajax({
		url: `[ci_site_url]util/get_time`,
		type: `GET`,
		success: function(res) {
			time = res;
		},
		error: function() {
			alert(`Неизвестная ошибка`); //TODO перевод
		}
	});
}

//получение сообщений из БД
function getNewMsgs(events, time) {
	$.ajax({
		url: `[ci_site_url]util/get_events`,
		type: `POST`,
		data: {
			events: events,
			time: time
		},
		success: function(data) {
			time = data.time;
			if (!document.getElementById(`card`).disabled) { //если меню неизвестных карт активно
				if (data.msgs.length > 0) {
					let o = confirm(`Введен неизвестный ключ. Выбрать его в качестве нового ключа пользователя?`); //TODO перевод
					if (o) {
						let card = data.msgs[data.msgs.length - 1].card_id; //последний прочитанный ключ из БД
						getCards(card);
					}
				}
			} else if (document.getElementById(`card_selector`).hidden) {
				if (data.msgs.length > 0) {
					let o = confirm(`Введен неизвестный ключ. Добавить его текущему пользователю?`); //TODO перевод
					if (o) {
						let card = data.msgs[data.msgs.length - 1].card_id; //последний прочитанный ключ из БД
						saveCard(card);
					}
				}
			}
			setTimeout(function() {
				getNewMsgs(events, time);
			}, 100);
		},
		error: function() {
			alert(`Неизвестная ошибка`); //TODO перевод
		}
	});
}
