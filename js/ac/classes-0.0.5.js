//удалить из базы
function deleteDivision(div_id) {
	if (!confirm(`Подтвердите удаление.`)) {
		return;
	}
	//отправим JSON
	$.ajax({
		url: `/index.php/db/delete_div`,
		type: `POST`,
		data: {
			div_id: div_id
		},
		success: function(res) {
			try {
				if (res == `ok`) {
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
	let number = document.getElementById(`number`).value;
	let letter = document.getElementById(`letter`).value;
	if (!number || !letter) {
		alert(`Введены не все данные`);
		return;
	}
	$.ajax({
		url: `/index.php/db/save_div`,
		type: `POST`,
		data: {
			number: number,
			letter: letter,
			org_id: org_id
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
