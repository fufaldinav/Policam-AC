let pers = {}, persInfo, events = [2,3]; //где 2,3 - события запрещенного входа/выхода

document.addEventListener("DOMContentLoaded", function() {
  persInfo = document.forms.pers_info;
});

//обновление информации пользователя в БД
function updatePersInfo() {
  let f = persInfo.elements;

  if (!persInfo.checkValidity()) {
    alert(`Введены не все данные`);
    return;
  }

  Object.keys(f).map(function(k, index) { //перебор элементов формы
    if (f[k].name == `photo`) {
      f[k].value = null;
    } else if (f[k].value) {
      pers[f[k].name] = f[k].value;
    } else {
      pers[f[k].name] = null;
    }
  });
  //отправим JSON
	$.ajax({
		url: `/index.php/ac/update_pers`,
		type: `POST`,
		data: {
			data: JSON.stringify(pers)
		},
		success: function(res) {
      try {
        if (res) {
          getCardsByPers(res);
          alert(`Пользователь №${res} успешно сохранен`);
        } else {
          alert(`Неизвестная ошибка`);
        }
      } catch(e) {
        sendError(e);
        alert(`Ошибка: ${e.name}: ${e.message}`);
      }
		},
    error: function() {
      alert(`Неизвестная ошибка`);
    }
	});
}

//удаление пользователя из БД
function deletePers() {
  let o = confirm(`Подтвердите удаление.`);
  if (!o) {
    return;
  }
  $.ajax({
		url: `/index.php/ac/delete_pers`,
		type: `POST`,
		data: {
      pers: pers.id
		},
		success: function(res) {
      try {
        if (res) {
          let f = persInfo.elements;
          Object.keys(f).map(function(k, index) { //перебор элементов формы
            if (f[k].name == `card_menu`) { //поставить в карты "Не выбрано"
              f[k].value = 0;
            } else if (f[k].name != `class`) { //обнулить значение всех полей, кроме Класс
              f[k].value = null;
            }
            if (k == `photo`) { //скрыть поле загрузки фото
              f[k].hidden = true;
            } else { //запретить редактирование полей
              f[k].readOnly = true;
            }
          });
          document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/0.jpg)';
          let li = document.getElementById(`pers${pers.id}`); //текущий элемент в ветке
          let ul = li.parentElement;  //родитель этого элемента
          li.remove(); //удаляем элемент
          ul.lastElementChild.classList.add(`IsLast`); //устанавливаем последний элемент в ветке
          for (let k in pers) {
            pers[k] = null;
          }
          document.getElementById(`cards`).innerHTML = ``; //очистка списка привязанных карт
          document.getElementById(`card_selector`).hidden = false; //отобразим меню с неизвестными картами
          document.getElementById(`card_menu`).disabled = true; //но запретим редактирование
          document.getElementById(`photo`).onchange = function() { return false; };
          document.getElementById(`photo_del`).onclick = function() { return false; };
          document.getElementById(`photo_del`).hidden = true;
          document.getElementById(`save`).onclick = function() { return false; };
          document.getElementById(`delete`).onclick = function() { return false; };
          alert(`Пользователь №${res} успешно удален`);
        } else {
          alert(`Неизвестная ошибка`);
        }
      } catch(e) {
        sendError(e);
        alert(`Ошибка: ${e.name}: ${e.message}`);
      }
		},
    error: function() {
      alert(`Неизвестная ошибка`);
    }
	});
}

//получение данных пользователя из БД
function getPersData(pers_id) {
  $.ajax({
    url: '/index.php/ac/get_pers',
    type: 'POST',
    data: {
      pers: pers_id
    },
    success: function(data) {
      try {
        if (data) {
          data = JSON.parse(data);
          Object.keys(data).map(function(k, index) { //перебор полученных данных
            pers[k] = data[k];
            if (document.getElementById(k)) { //существует ли элемент с id = свойство объекта, т.к. могут быть "посторонние" данные
              if (k == `photo`) { //отобразим поле загрузки фото
                document.getElementById(k).value = null;
              } else { //в остальные поля запишем данные и разрешим для записи
                document.getElementById(k).value = data[k];
                document.getElementById(k).readOnly = false;
              }
            }
          });
          let photo = document.getElementById(`photo_bg`);
          if (!data.photo) {
            data.photo = `0`;
            document.getElementById(`photo`).hidden = false;
            document.getElementById(`photo_del`).hidden = true;
            document.getElementById(`photo_del`).onclick = function() { return false; };
          } else {
            document.getElementById(`photo`).hidden = true;
            document.getElementById(`photo_del`).hidden = false;
            document.getElementById(`photo_del`).onclick = deletePhoto;
          }
          photo.style.backgroundImage = 'url(/img/ac/s/' + data.photo + '.jpg)';
          document.getElementById(`photo`).onchange = function() { handleFiles(this.files); };
          document.getElementById(`save`).onclick = updatePersInfo;
          document.getElementById(`delete`).onclick = deletePers;
          getCardsByPers(pers.id);
        } else {
          alert(`Неизвестная ошибка`);
        }
      } catch(e) {
        sendError(e);
        alert(`Ошибка удаления: ${e.name}: ${e.message}`);
      }
    },
    error: function() {
      alert(`Неизвестная ошибка`);
    }
  });
}

//получение списка карт (брелоков) от сервера
function getCardsByPers(pers_id) {
  $.ajax({
    url: '/index.php/ac/get_cards_by_pers',
    type: 'POST',
    data: {
      holder_id: pers_id
    },
    success: function(data) {
      try {
        let cards = document.getElementById(`cards`);
        cards.innerHTML = ``;
        if (data) {
          document.getElementById(`card_selector`).hidden = true; //спрячем неизвестные карты
          document.getElementById(`card_menu`).disabled = true; //отключим меню неизвеснтых карт
          data = JSON.parse(data);
          data.forEach(function(c) { //добавим каждую карту в список привязанных
            cards.innerHTML = cards.innerHTML + `<div id="card${c.id}">${c.wiegand} <button type="button" onclick="delCard(${c.id});">Отвязать</button><br /></div>`
       		});
          let li = document.getElementById(`pers${pers.id}`); //добавим пользователю метку наличия ключей
          let c = li.querySelector('.Content');
          if (c.innerHTML.indexOf(`(+) `) == -1) {
            c.innerHTML = `(+) ${c.innerHTML}`;
          }
        } else {
          document.getElementById(`card_selector`).hidden = false; //отобразим неизвестные карты
          document.getElementById(`card_menu`).disabled = false; //включим меню неизвеснтых карт
          getCards();
        }
      } catch(e) {
        sendError(e);
        alert(`Ошибка удаления: ${e.name}: ${e.message}`);
      }
    },
    error: function() {
      alert(`Неизвестная ошибка`);
    }
  });
}

//добавление карты в БД
function saveCard(card) {
  //отправим JSON
	$.ajax({
		url: `/index.php/ac/add_card`,
		type: `POST`,
		data: {
			card: card,
			pers: pers.id
		},
		success: function(res) {
			try {
				if (res == `ok`) {
					getCardsByPers(pers.id);
					alert(`Ключ успешно добавлен`);
				} else {
					alert(`Неизвестная ошибка`);
				}
			} catch(e) {
        sendError(e);
        alert(`Ошибка: ${e.name}: ${e.message}`);
			}
		},
		error: function() {
			alert(`Неизвестная ошибка`);
		}
	});
}

//удаление карты из БД
function delCard(id) {
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
          let card = document.getElementById(`card${id}`);
          card.remove(); //удалим карту из списка привязанных
          let cardsHtml = document.getElementById(`cards`).innerHTML;
          cardsHtml = (cardsHtml.trim) ? cardsHtml.trim() : cardsHtml.replace(/^\s+/,'');
          if (cardsHtml == ``) { //если список привязанных карт пуст, то отобразим и включим меню и запросим неизвеснтые карты
            document.getElementById(`card_selector`).hidden = false;
            document.getElementById(`card_menu`).disabled = false;
            getCards();
            let li = document.getElementById(`pers${pers.id}`); //удалим у пользователя метку наличия ключей
            let c = li.querySelector('.Content');
            if (c.innerHTML.indexOf(`(+) `) == 0) {
              c.innerHTML = c.innerHTML.substring(4);
            }
          }
          alert(`Ключ успешно отвязан`);
        } else {
          alert(`Неизвестная ошибка`);
        }
      } catch(e) {
        sendError(e);
        alert(`Ошибка: ${e.name}: ${e.message}`);
      }
		},
    error: function() {
      alert(`Неизвестная ошибка`);
    }
	});
}
