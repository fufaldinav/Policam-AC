//удалить из базы
function del(id) {
	if (!confirm(`Подтвердите удаление.`)) {
		return;
	}
	//отправим JSON
	$.ajax({
		url: `/index.php/db/delete_class`,
		type: `POST`,
		data: {
			class: id
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
function save(id) {
	let number = document.getElementById(`number`).value;
	let letter = document.getElementById(`letter`).value;
	if (!number || !letter) {
		alert(`Введены не все данные`);
		return;
	}
	$.ajax({
		url: `/index.php/db/save_class`,
		type: `POST`,
		data: {
			number: number,
			letter: letter,
			school: id
		},
		success: function(res) {
			if (res) {
				let c = JSON.parse(res);
				alert(`Класс ${c.number} "${c.letter}" успешно сохранен`);
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
