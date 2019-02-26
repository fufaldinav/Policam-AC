let time, events = [4,5]; //где 4,5 - события разрешенного входа/выхода

document.addEventListener("DOMContentLoaded", function() {
	getClasses();
	time = getServerTime();
	getNewMsgs(events, time);
});

//получение времени от сервера
function getServerTime() {
	$.ajax({
		url: `/index.php/util/get_time`,
		success: function(data) {
		time = data;
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
		success: function(data) {
			try {
				if (data) {
					data = JSON.parse(data);
					time = data.time;
					if (data.msgs.length > 0) {
						let card = data.msgs[data.msgs.length - 1].card_id; //последний прочитанный ключ из БД
						setPersData(card);
					}
					setTimeout(function() {
						getNewMsgs(events, time);
					}, 10);
				} else {
					alert(`Пустой ответ от сервера`);
				}
			} catch(e) {
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
function setPersData(card) {
	$.ajax({
		url: `/index.php/db/get_pers`,
		type: `POST`,
		data: {
			card: card
		},
		success: function(data) {
			try {
				if (data) {
					data = JSON.parse(data);
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
			} catch(e) {
				sendError(e);
				alert(`Ошибка: ${e.name}: ${e.message}`);
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`);
		}
	});
}

function getClasses() {
	$.ajax({
		url: `/index.php/db/get_classes`,
		type: `GET`,
		success: function(data) {
			try {
				if (data) {
					data = JSON.parse(data);
					let classList = ``;
					data.forEach(function(c) {
						classList += `<div id="class${c.id}" class="menu-item" onclick="getPersonal(${c.id});">${c.number} "${c.letter}"</div>`;
					});
					let menu = document.getElementById(`menu`);
					menu.innerHTML = classList;
				} else {
					alert(`Пустой ответ от сервера`);
				}
			} catch(e) {
				sendError(e);
				alert(`Ошибка: ${e.name}: ${e.message}`);
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`);
		}
	});
}

function getPersonal(class_id) {
	$.ajax({
		url: `/index.php/db/get_personal/${class_id}`,
		type: `GET`,
		success: function(data) {
			try {
				if (data) {
					data = JSON.parse(data);
					let personal = `<div id="menu-button-back" class="menu-item" onclick="getClasses();">Назад</div>`;
					data.forEach(function(pers) {
						personal += `<div id="pers${pers.id}" class="menu-item" onclick="openEntraceOptions(${pers.id}, ${class_id});">${pers.f} ${pers.i}</div>`;
					});
					let menu = document.getElementById(`menu`);
					menu.innerHTML = personal;
				} else {
					alert(`Пустой ответ от сервера`);
				}
			} catch(e) {
				sendError(e);
				alert(`Ошибка: ${e.name}: ${e.message}`);
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`);
		}
	});
}

function openEntraceOptions(pers_id, class_id) {
	let options = ``;
	if (class_id === undefined) {
		options += `<div id="menu-button-back" class="menu-item" onclick="getClasses();">Назад</div>`;
	} else {
		options += `<div id="menu-button-back" class="menu-item" onclick="getPersonal(${class_id});">Назад</div>`;
	}
	options += `<div id="menu-button-forgot" class="menu-item" onclick="sendInfo(1, ${pers_id})">Забыл</div>`;
	options += `<div id="menu-button-lost" class="menu-item" onclick="sendInfo(2, ${pers_id})">Потерял</div>`;
	options += `<div id="menu-button-broke" class="menu-item" onclick="sendInfo(3, ${pers_id})">Сломал</div>`;
	let menu = document.getElementById(`menu`);
	menu.innerHTML = options;
}

function sendInfo(type, pers_id) {
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
			pers_id: pers_id
		},
		success: function(data) {
			try {
				if (data) {
					alert(data);
				} else {
					alert(`Пустой ответ от сервера`);
				}
			} catch(e) {
				sendError(e);
				alert(`Ошибка: ${e.name}: ${e.message}`);
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`);
		}
	});
}
