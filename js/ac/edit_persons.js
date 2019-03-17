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
	photos = [];

//обновление информации пользователя в БД
function updatePersonInfo() {
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
			url: `[ci_site_url]persons/update`,
			type: `POST`,
			data: {
				cards: JSON.stringify(cards),
				person: JSON.stringify(person),
				photos: JSON.stringify(photos)
			},
			success: function(res) {
				if (res > 0) {
					alert(`Пользователь успешно сохранен`); //TODO перевод
				} else {
					alert(`Не сохранено или данные совпали`); //TODO перевод
				}
				getCardsByPerson(person.id);
			},
			error: function() {
				alert(`Неизвестная ошибка`); //TODO перевод
			}
		});
	}
}

//удаление пользователя из БД
function deletePerson() {
	if (!confirm(`Подтвердите удаление.`)) { //TODO перевод
		return;
	}
	$.ajax({
		url: `[ci_site_url]persons/delete/${person.id}`,
		type: `GET`,
		success: function(res) {
			if (res > 0) {
				let currentElement = document.getElementById(`person${person.id}`);
				let parentElement = currentElement.parentElement; //родитель этого элемента
				currentElement.remove(); //удаляем элемент
				let lastElement = parentElement.lastElementChild;
				if (lastElement !== null) {
					lastElement.classList.add(`tree-is-last`); //устанавливаем последний элемент в ветке
				}

				for (let k in person) {
					let elem = document.getElementById(k);
					elem.value = null;
					elem.readOnly = true;
					person[k] = null;
				}

				photos = [];
				document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/0.jpg)';
				document.getElementById(`photo`).hidden = true;
				document.getElementById(`photo`).onchange = function() {
					return false;
				};
				document.getElementById(`photo_del`).onclick = function() {
					return false;
				};
				document.getElementById(`photo_del`).hidden = true;

				cards = [];
				document.getElementById(`cards`).value = 0;
				document.getElementById(`person_cards`).innerHTML = ``; //очистка списка привязанных карт
				document.getElementById(`unknown_cards`).hidden = false; //отобразим меню с неизвестными картами
				document.getElementById(`cards`).disabled = true; //но запретим редактирование

				document.getElementById(`save`).onclick = function() {
					return false;
				};
				document.getElementById(`delete`).onclick = function() {
					return false;
				};
				alert(`Пользователь успешно удален`); //TODO перевод
			} else {
				alert(`Пустой ответ от сервера`); //TODO перевод
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`); //TODO перевод
		}
	});
}

//получение данных пользователя из БД
function getPersonInfo(person_id) {
	$.ajax({
		url: `[ci_site_url]persons/get/${person_id}`,
		type: `GET`,
		success: function(data) {
			if (data) {
				for (let k in data.person) {
					person[k] = data.person[k];
					document.getElementById(k).value = data.person[k];
					document.getElementById(k).readOnly = false;
				}

				let photo_id = 0;
				photos = [];
				document.getElementById(`photo`).value = null;
				if (data.photos.length === 0) {
					document.getElementById(`photo`).hidden = false;
					document.getElementById(`photo_del`).hidden = true;
					document.getElementById(`photo_del`).onclick = function() {
						return false;
					};
				} else {
					photo_id = data.photos[0].id;
					photos.unshift(photo_id);
					document.getElementById(`photo`).hidden = true;
					document.getElementById(`photo_del`).hidden = false;
					document.getElementById(`photo_del`).onclick = deletePhoto;
				}
				document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/' + photo_id + '.jpg)';
				document.getElementById(`photo`).onchange = function() {
					handleFiles(this.files);
				};

				document.getElementById(`save`).onclick = updatePersonInfo;
				document.getElementById(`delete`).onclick = deletePerson;

				getCardsByPerson(person.id);
			} else {
				alert(`Пустой ответ от сервера`); //TODO перевод
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`); //TODO перевод
		}
	});
}

//получение списка карт (брелоков) от сервера
function getCardsByPerson(person_id) {
	$.ajax({
		url: `[ci_site_url]cards/get_by_person/${person_id}`,
		type: `GET`,
		success: function(data) {
			cards = [];
			let person_cards = document.getElementById(`person_cards`);
			person_cards.innerHTML = ``;
			if (data.length > 0) {
				document.getElementById(`unknown_cards`).hidden = true; //спрячем неизвестные карты
				document.getElementById(`cards`).disabled = true; //отключим меню неизвеснтых карт
				for (let k in data) { //добавим каждую карту в список привязанных
					person_cards.innerHTML += `<div id="card${data[k].id}">${data[k].wiegand} <button type="button" onclick="delCard(${data[k].id});">Отвязать</button><br /></div>`
				}
				let li = document.getElementById(`person${person.id}`); //добавим пользователю метку наличия ключей
				let a = li.querySelector(`.person`);
				a.classList.remove(`no-card`);
			} else {
				document.getElementById(`unknown_cards`).hidden = false; //отобразим неизвестные карты
				document.getElementById(`cards`).disabled = false; //включим меню неизвеснтых карт
				getCards();
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`); //TODO перевод
		}
	});
}

//добавление карты в БД
function saveCard(card_id) {
	$.ajax({
		url: `[ci_site_url]cards/holder/${card_id}/${person.id}`,
		type: `GET`,
		success: function(res) {
			if (res > 0) {
				getCardsByPerson(person.id);
				alert(`Ключ успешно добавлен`); //TODO перевод
			} else {
				alert(`Неизвестная ошибка`); //TODO перевод
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`); //TODO перевод
		}
	});
}

//удаление карты из БД
function delCard(card_id) {
	if (!confirm(`Подтвердите удаление.`)) { //TODO перевод
		return;
	}
	$.ajax({
		url: `[ci_site_url]cards/holder/${card_id}`,
		type: `GET`,
		success: function(res) {
			if (res > 0) {
				let card = document.getElementById(`card${card_id}`);
				card.remove(); //удалим карту из списка привязанных
				let cardsHtml = document.getElementById(`person_cards`).innerHTML;
				cardsHtml = (cardsHtml.trim) ? cardsHtml.trim() : cardsHtml.replace(/^\s+/, ``);
				if (cardsHtml == ``) { //если список привязанных карт пуст, то отобразим и включим меню и запросим неизвеснтые карты
					document.getElementById(`unknown_cards`).hidden = false;
					document.getElementById(`cards`).disabled = false;
					getCards();
					let li = document.getElementById(`person${person.id}`); //удалим у пользователя метку наличия ключей
					let a = li.querySelector(`.person`);
					a.classList.add(`no-card`);
				}
				alert(`Ключ успешно отвязан`); //TODO перевод
			} else {
				alert(`Неизвестная ошибка`); //TODO перевод
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`); //TODO перевод
		}
	});
}
