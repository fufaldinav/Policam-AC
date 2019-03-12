let div = {
	'number': null,
	'letter': null,
	'org_id': null
};

//удалить из базы
function deleteDivision(div_id) {
	if (!confirm(`Подтвердите удаление.`)) { //TODO перевод
		return;
	}
	$.ajax({
		url: `[ci_base_url]divisions/delete/${div_id}`,
		type: `GET`,
		success: function(res) {
			if (res > 0) {
				alert(`Успешное удаление`); //TODO перевод
				location.reload();
			} else {
				alert(`Неизвестная ошибка`); //TODO перевод
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`); //TODO перевод
		}
	});
}

//сохранить в базу
function saveDivision(org_id) {
	div.number = document.getElementById(`number`).value;
	div.letter = document.getElementById(`letter`).value;
	div.org_id = org_id;
	if (!number || !letter) {
		alert(`Введены не все данные`); //TODO перевод
		return;
	}
	$.ajax({
		url: `[ci_base_url]divisions/add`,
		type: `POST`,
		data: {
			div: JSON.stringify(div)
		},
		success: function(div) {
			alert(`Класс ${div.number} "${div.letter}" успешно сохранен`); //TODO перевод
			location.reload();
		},
		error: function() {
			alert(`Неизвестная ошибка`); //TODO перевод
		}
	});
}
