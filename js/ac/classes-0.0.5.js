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
	//отправим JSON
	$.ajax({
		url: `/index.php/divisions/delete/${div_id}`,
		type: `GET`,
		success: function(res) {
			try {
				if (res > 0) {
					alert(`Успешное удаление`);
					location.reload();
				} else {
					alert(`Неизвестная ошибка`);
				}
			} catch (e) {
				alert(`Ошибка удаления: ${e.name}: ${e.message}`);
				sendError(e);
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
		url: `/index.php/divisions/add`,
		type: `POST`,
		data: {
			div: JSON.stringify(div)
		},
		success: function(res) {
			if (res) {
				let div = JSON.parse(res);
				alert(`Класс ${div.number} "${div.letter}" успешно сохранен`);
				location.reload();
			} else {
				alert(`Пустой ответ от сервера`);
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`);
		}
	});
}
