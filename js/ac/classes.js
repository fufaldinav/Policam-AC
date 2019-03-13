let div = {
	'name': null,
	'org_id': null
};

//удалить из базы
function deleteDivision(div_id) {
	if (!confirm(`Подтвердите удаление.`)) { //TODO перевод
		return;
	}
	$.ajax({
		url: `[ci_site_url]divisions/delete/${div_id}`,
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
	let number = document.getElementById(`number`).value;
	let letter = document.getElementById(`letter`).value;
	if (!number || !letter) {
		alert(`Введены не все данные`); //TODO перевод
		return;
	}
	div.name = `${number} "${letter}"`;
	div.org_id = org_id;
	$.ajax({
		url: `[ci_site_url]divisions/add`,
		type: `POST`,
		data: {
			div: JSON.stringify(div)
		},
		success: function(div) {
			alert(`Класс ${div.name} успешно сохранен`); //TODO перевод
			location.reload();
		},
		error: function() {
			alert(`Неизвестная ошибка`); //TODO перевод
		}
	});
}
