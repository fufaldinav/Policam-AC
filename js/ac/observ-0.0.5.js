let time, events = [4, 5]; //где 4,5 - события разрешенного входа/выхода

document.addEventListener("DOMContentLoaded", function() {
	getDivisions();
	time = getServerTime();
	getNewMsgs(events, time);
});

//получение времени от сервера
function getServerTime() {
	$.ajax({
		url: `/index.php/util/get_time`,
		success: function(res) {
			time = res;
		},
		type: `GET`
	});
}

//получение сообщений от сервера
function getNewMsgs(events, time) {
	$.ajax({
		url: `/index.php/util/get_events`,
		type: `POST`,
		data: {
			events: events,
			time: time
		},
		success: function(res) {
			try {
				if (res) {
					let data = JSON.parse(res);
					time = data.time;
					if (data.msgs.length > 0) {
						let card = data.msgs[data.msgs.length - 1].card_id; //последний прочитанный ключ из БД
						setPersonInfo(card);
					}
					setTimeout(function() {
						getNewMsgs(events, time);
					}, 10);
				} else {
					alert(`Пустой ответ от сервера`);
				}
			} catch (e) {
				sendError(e);
				alert(`Ошибка: ${e.name}: ${e.message}`);
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`);
		}
	});
}

//получение данных пользователя из БД
function setPersonInfo(card_id) {
	$.ajax({
		url: `/index.php/persons/get_by_card/${card_id}`,
		type: `GET`,
		success: function(res) {
			try {
				if (res) {
					let data = JSON.parse(res);
					Object.keys(data).map(function(k) { //перебор полученных данных
						if (document.getElementById(k)) {
							document.getElementById(k).value = data[k];
						}
					});
					let photo = document.getElementById(`photo_bg`);
					if (!data.photo) {
						data.photo = `0`;
					}
					photo.style.backgroundImage = 'url(/img/ac/s/' + data.photo + '.jpg)';
				} else {
					alert(`Пустой ответ от сервера`);
				}
			} catch (e) {
				sendError(e);
				alert(`Ошибка: ${e.name}: ${e.message}`);
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`);
		}
	});
}

function getDivisions() {
	$.ajax({
		url: `/index.php/divisions/get_all`,
		type: `GET`,
		success: function(res) {
			try {
				if (res) {
					let data = JSON.parse(res);
					let divisions = ``;
					data.forEach(function(div) {
						divisions += `<div id="div${div.id}" class="menu-item" onclick="getPersons(${div.id});">${div.number} "${div.letter}"</div>`;
					});
					let menu = document.getElementById(`menu`);
					menu.innerHTML = divisions;
				} else {
					alert(`Пустой ответ от сервера`);
				}
			} catch (e) {
				sendError(e);
				alert(`Ошибка: ${e.name}: ${e.message}`);
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`);
		}
	});
}

function getPersons(div_id) {
	$.ajax({
		url: `/index.php/persons/get_all/${div_id}`,
		type: `GET`,
		success: function(res) {
			try {
				if (res) {
					let data = JSON.parse(res);
					let persons = `<div id="menu-button-back" class="menu-item" onclick="getDivisions();">Назад</div>`;
					data.forEach(function(person) {
						persons += `<div id="person${person.id}" class="menu-item" onclick="openEntraceOptions(${person.id}, ${div_id});">${person.f} ${person.i}</div>`;
					});
					let menu = document.getElementById(`menu`);
					menu.innerHTML = persons;
				} else {
					alert(`Пустой ответ от сервера`);
				}
			} catch (e) {
				sendError(e);
				alert(`Ошибка: ${e.name}: ${e.message}`);
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`);
		}
	});
}

function openEntraceOptions(person_id, div_id) {
	let options = ``;
	if (div_id === undefined) {
		options += `<div id="menu-button-back" class="menu-item" onclick="getDivisions();">Назад</div>`;
	} else {
		options += `<div id="menu-button-back" class="menu-item" onclick="getPersons(${div_id});">Назад</div>`;
	}
	options += `<div id="menu-button-forgot" class="menu-item" onclick="sendInfo(1, ${person_id})">Забыл</div>`;
	options += `<div id="menu-button-lost" class="menu-item" onclick="sendInfo(2, ${person_id})">Потерял</div>`;
	options += `<div id="menu-button-broke" class="menu-item" onclick="sendInfo(3, ${person_id})">Сломал</div>`;
	let menu = document.getElementById(`menu`);
	menu.innerHTML = options;
}

function sendInfo(type, person_id) {
	let msg;
	switch (type) {
		case 1:
			msg = `На сервер будет отправлено уведомление.`;
			if (!confirm(msg)) return;
			break;
		case 2:
			msg = `Карта будет удалена, а на сервер будет отправлено уведомление.`;
			if (!confirm(msg)) return;
			break;
		case 3:
			msg = `Карта будет удалена, а на сервер будет отправлено уведомление.`;
			if (!confirm(msg)) return;
			break;
	}
	$.ajax({
		url: `/index.php/util/card_problem`,
		type: `POST`,
		data: {
			type: type,
			person_id: person_id
		},
		success: function(res) {
			try {
				if (res) {
					alert(res);
				} else {
					alert(`Пустой ответ от сервера`);
				}
			} catch (e) {
				sendError(e);
				alert(`Ошибка: ${e.name}: ${e.message}`);
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`);
		}
	});
}
