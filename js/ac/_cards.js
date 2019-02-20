//удалить из базы
function del(id) {
  let o = confirm(`Подтвердите удаление.`);
  if (!o) {
    return;
  }
  //отправим JSON
	$.ajax({
		url: `/index.php/ac/delete_card`,
		type: `POST`,
		data: { card: id },
		success: function(res) {
      try {
        if (res == `ok`) {
          alert(`Ключ успешно отвязан`);
          location.reload();
        } else {
          alert(`Неизвестная ошибка`);
        }
      } catch(e) {
        alert(`Ошибка: ${e.name}: ${e.message}`);
      }
		},
    error: function() {
      alert(`Неизвестная ошибка`);
    }
	});
}
