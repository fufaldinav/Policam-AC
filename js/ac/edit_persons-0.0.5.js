let events = [2, 3]; //где 2,3 - события запрещенного входа/выхода
let person = {
	'f': null,
	'i': null,
	'o': null,
	'photo': null,
	'div': null,
	'birthday': null,
	'address': null,
	'phone': null,
	'card': null
}
//обновление информации пользователя в БД
function updatePersonInfo() {
	let checkValidity = true;
	Object.keys(person).map(function(k) {
		let elem = document.getElementById(k);
		if (elem.required && elem.value === ``) {
			elem.classList.add(`no-data`);
			checkValidity = false;
		}
		if (k == `photo`) {
			//TODO
		} else if (elem.value) {
			person[k] = elem.value;
		} else {
			person[k] = null;
		}
	});
	if (!checkValidity) {
		alert(`Введены не все данные`);
	} else {
		$.ajax({
			url: `/index.php/db/update_person`,
			type: `POST`,
			data: {
				person: JSON.stringify(person)
			},
			success: function(res) {
				try {
					if (res) {
						if (res > 0) {
							alert(`Пользователь успешно сохранен`);
						} else {
							alert(`Не сохранено или данные совпали`);
						}
						getCardsByPerson(person.id);
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
}
//удаление пользователя из БД
function deletePerson() {
	if (!confirm(`Подтвердите удаление.`)) {
		return;
	}
	$.ajax({
		url: `/index.php/db/delete_person/${person.id}`,
		type: `GET`,
		success: function(res) {
			try {
				if (res) {
					Object.keys(person).map(function(k) { //перебор элементов формы
						let elem = document.getElementById(k);
						if (k == `card`) { //поставить в карты "Не выбрано"
							elem.value = 0;
						} else if (k != `div`) { //обнулить значение всех полей, кроме Класс
							elem.value = null;
						}
						if (k == `photo`) { //скрыть поле загрузки фото
							elem.hidden = true;
						} else { //запретить редактирование полей
							elem.readOnly = true;
						}
					});
					document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/0.jpg)';
					let li = document.getElementById(`person${person.id}`); //текущий элемент в ветке
					let ul = li.parentElement; //родитель этого элемента
					li.remove(); //удаляем элемент
					ul.lastElementChild.classList.add(`tree-is-last`); //устанавливаем последний элемент в ветке
					for (let k in person) {
						person[k] = null;
					}
					document.getElementById(`cards`).innerHTML = ``; //очистка списка привязанных карт
					document.getElementById(`card_selector`).hidden = false; //отобразим меню с неизвестными картами
					document.getElementById(`card`).disabled = true; //но запретим редактирование
					document.getElementById(`photo`).onchange = function() {
						return false;
					};
					document.getElementById(`photo_del`).onclick = function() {
						return false;
					};
					document.getElementById(`photo_del`).hidden = true;
					document.getElementById(`save`).onclick = function() {
						return false;
					};
					document.getElementById(`delete`).onclick = function() {
						return false;
					};
					alert(`Пользователь №${res} успешно удален`);
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
function getPersonInfo(person_id) {
	$.ajax({
		url: `/index.php/db/get_person/${person_id}`,
		type: `GET`,
		success: function(res) {
			try {
				if (res) {
					let data = JSON.parse(res);
					Object.keys(data).map(function(k) { //перебор полученных данных
						person[k] = data[k];
						if (document.getElementById(k)) { //существует ли элемент с id = свойство объекта, т.к. могут быть "посторонние" данные
							if (k == `photo`) { //отобразим поле загрузки фото
								document.getElementById(k).value = null;
							} else { //в остальные поля запишем данные и разрешим для записи
								document.getElementById(k).value = data[k];
								document.getElementById(k).readOnly = false;
							}
						}
					});
					let photo = document.getElementById(`photo_bg`);
					if (!data.photo) {
						data.photo = `0`;
						document.getElementById(`photo`).hidden = false;
						document.getElementById(`photo_del`).hidden = true;
						document.getElementById(`photo_del`).onclick = function() {
							return false;
						};
					} else {
						document.getElementById(`photo`).hidden = true;
						document.getElementById(`photo_del`).hidden = false;
						document.getElementById(`photo_del`).onclick = deletePhoto;
					}
					photo.style.backgroundImage = 'url(/img/ac/s/' + data.photo + '.jpg)';
					document.getElementById(`photo`).onchange = function() {
						handleFiles(this.files);
					};
					document.getElementById(`save`).onclick = updatePersonInfo;
					document.getElementById(`delete`).onclick = deletePerson;
					getCardsByPerson(person.id);
				} else {
					alert(`Пустой ответ от сервера`);
				}
			} catch (e) {
				sendError(e);
				alert(`Ошибка удаления: ${e.name}: ${e.message}`);
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`);
		}
	});
}
//получение списка карт (брелоков) от сервера
function getCardsByPerson(person_id) {
	$.ajax({
		url: `/index.php/db/get_cards_by_person/${person_id}`,
		type: `GET`,
		success: function(res) {
			try {
				let cards = document.getElementById(`cards`);
				cards.innerHTML = ``;
				if (res) {
					document.getElementById(`card_selector`).hidden = true; //спрячем неизвестные карты
					document.getElementById(`card`).disabled = true; //отключим меню неизвеснтых карт
					let data = JSON.parse(res);
					data.forEach(function(c) { //добавим каждую карту в список привязанных
						cards.innerHTML = cards.innerHTML + `<div id="card${c.id}">${c.wiegand} <button type="button" onclick="delCard(${c.id});">Отвязать</button><br /></div>`
					});
					let li = document.getElementById(`person${person.id}`); //добавим пользователю метку наличия ключей
					let c = li.querySelector(`.tree-content`);
					if (c.innerHTML.indexOf(`(+) `) == -1) {
						c.innerHTML = `(+) ${c.innerHTML}`;
					}
				} else {
					document.getElementById(`card_selector`).hidden = false; //отобразим неизвестные карты
					document.getElementById(`card`).disabled = false; //включим меню неизвеснтых карт
					getCards();
				}
			} catch (e) {
				sendError(e);
				alert(`Ошибка удаления: ${e.name}: ${e.message}`);
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`);
		}
	});
}
//добавление карты в БД
function saveCard(card_id) {
	$.ajax({
		url: `/index.php/db/add_card/${card_id}/${person.id}`,
		type: `GET`,
		success: function(res) {
			try {
				if (res == `ok`) {
					getCardsByPerson(person.id);
					alert(`Ключ успешно добавлен`);
				} else {
					alert(`Неизвестная ошибка`);
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
//удаление карты из БД
function delCard(card_id) {
	if (!confirm(`Подтвердите удаление.`)) {
		return;
	}
	$.ajax({
		url: `/index.php/db/delete_card/${card_id}`,
		type: `GET`,
		success: function(res) {
			try {
				if (res == `ok`) {
					let card = document.getElementById(`card${id}`);
					card.remove(); //удалим карту из списка привязанных
					let cardsHtml = document.getElementById(`cards`).innerHTML;
					cardsHtml = (cardsHtml.trim) ? cardsHtml.trim() : cardsHtml.replace(/^\s+/, ``);
					if (cardsHtml == ``) { //если список привязанных карт пуст, то отобразим и включим меню и запросим неизвеснтые карты
						document.getElementById(`card_selector`).hidden = false;
						document.getElementById(`card`).disabled = false;
						getCards();
						let li = document.getElementById(`person${person.id}`); //удалим у пользователя метку наличия ключей
						let c = li.querySelector(`.tree-content`);
						if (c.innerHTML.indexOf(`(+) `) == 0) {
							c.innerHTML = c.innerHTML.substring(4);
						}
					}
					alert(`Ключ успешно отвязан`);
				} else {
					alert(`Неизвестная ошибка`);
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
