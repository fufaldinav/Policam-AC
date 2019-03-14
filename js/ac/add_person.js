let events = [2, 3]; //где 2,3 - события запрещенного входа/выхода
let person = {
	'f': null,
	'i': null,
	'o': null,
	'photo': null,
	'birthday': null,
	'address': null,
	'phone': null,
	'cards': []
};
let divs = [];

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
	Object.keys(person).map(function(k, index) {
		let elem = document.getElementById(k);
		if (elem.required && elem.value === ``) {
			elem.classList.add(`no-data`);
			checkValidity = false;
		}
		if (k == `photo`) {
			//TODO
		} else if (k === `cards`) {
			if (elem.value > 0) {
				person[k].push(elem.value);
			}
		} else if (elem.value) {
			person[k] = elem.value;
		} else {
			person[k] = null;
		}
	});
	if (!checkValidity) {
		alert(`Введены не все данные`); //TODO перевод
	} else {
		$.ajax({
			url: `[ci_site_url]persons/add`,
			type: `POST`,
			data: {
				divs: JSON.stringify(divs),
				person: JSON.stringify(person)
			},
			success: function(person_id) {
				for (let k in person) {
					person[k] = null;
				}
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
	Object.keys(person).map(function(k, index) {
		let elem = document.getElementById(k);
		if (k === `cards`) {
			person[k] = [];
			elem.value = 0;
		} else {
			person[k] = null;
			elem.value = null;
		}
	});
	document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/0.jpg)';
	document.getElementById(`photo_del`).hidden = true;
	document.getElementById(`photo_del`).onclick = function() {
		return false;
	};
}

function checkData(e) {
	e.classList.remove(`no-data`);
}
