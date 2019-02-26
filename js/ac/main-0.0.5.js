//сохранить ошибку на сервере
function sendError(message) {
	$.ajax({
		url: `/index.php/util/save_js_errors`,
		type: `POST`,
		data: {
			data: message
		}
	});
}

//получим список неизвестных карт (брелоков) из БД
function getCards(id) {
	let card = document.getElementById(`card`);
	$.ajax({
		url: `/index.php/db/get_cards`,
		type: `GET`,
		success: function(data) {
			try {
				if (data) {
					data = JSON.parse(data);
					while (card.length > 0) { //удалить все элементы из меню карт
						card.remove(card.length - 1);
					}
					if (data.length == 0) { //если нет неизвестных карт
						addOption(card, 0, `Отсутствует`);
					} else { //иначе заполним меню картами
						addOption(card, 0, `Не выбрана`); //первый пункт
						data.forEach(function(c) {
							addOption(card, c.id, c.wiegand);
						});
					}
					if (id) { //если передавали id, то установим карту как текущую
						card.value = id;
					}
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
	//отправим JSON
	$.ajax({
		url: `/index.php/util/save_photo`,
		type: `POST`,
		method: `POST`,
		contentType: false,
		processData: false,
		data: formData,
		success: function(res) {
			try {
				if (res && res !== `0`) {
					document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/' + res + '.jpg)';
					pers.photo = res;
					document.getElementById(`photo`).hidden = true;
					document.getElementById(`photo_del`).hidden = false;
					document.getElementById(`photo_del`).onclick = deletePhoto;
				} else {
					document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/0.jpg)';
					pers.photo = null;
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

//удаление фото
function deletePhoto() {
	if (!confirm(`Подтвердите удаление.`)) {
		return;
	}
	$.ajax({
		url: `/index.php/util/delete_photo`,
		type: `POST`,
		data: {
			id: pers.id,
			photo: pers.photo
		},
		success: function(res) {
			try {
				if (res == `ok`) {
					document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/0.jpg)';
					document.getElementById(`photo_del`).hidden = true;
					document.getElementById(`photo_del`).onclick = function() { return false; };
					document.getElementById(`photo`).hidden = false;
					document.getElementById(`photo`).value = null;
					pers.photo = null;
				} else {
					alert(`Неизвестная ошибка`);
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
