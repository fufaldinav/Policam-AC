let div = {
	'number': null,
	'letter': null,
	'org_id': null
};

//удалить из базы
function deleteDivision(div_id) {
	if (!confirm(`Подтвердите удаление.`)) {
		return;
	}
	$.ajax({
		url: `/divisions/delete/${div_id}`,
		type: `GET`,
		success: function(res) {
			if (res > 0) {
				alert(`Успешное удаление`);
				location.reload();
			} else {
				alert(`Неизвестная ошибка`);
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`);
		}
	});
}

//сохранить в базу
function saveDivision(org_id) {
	div.number = document.getElementById(`number`).value;
	div.letter = document.getElementById(`letter`).value;
	div.org_id = org_id;
	if (!number || !letter) {
		alert(`Введены не все данные`);
		return;
	}
	$.ajax({
		url: `/divisions/add`,
		type: `POST`,
		data: {
			div: JSON.stringify(div)
		},
		success: function(div) {
			alert(`Класс ${div.number} "${div.letter}" успешно сохранен`);
			location.reload();
		},
		error: function() {
			alert(`Неизвестная ошибка`);
		}
	});
}
