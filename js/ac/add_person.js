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
};

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
			url: `[ci_base_url]persons/add`,
			type: `POST`,
			data: {
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
	document.getElementById(`card`).value = 0; //поставить в карты "Не выбрано"
	Object.keys(person).map(function(k, index) {
		let elem = document.getElementById(k);
		if (k != `div`) { //обнулить значение всех полей, кроме Класс
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
