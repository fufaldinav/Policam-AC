let pers = {}, persInfo, events = [2,3]; //где 2,3 - события запрещенного входа/выхода

document.addEventListener("DOMContentLoaded", function() {
  persInfo = document.forms.pers_info;
});

//сохранение пользователя в БД
function savePersInfo(e) {
  let f = persInfo.elements;
  if (e && !persInfo.checkValidity()) {
    alert(`Введены не все данные`);
    return;
  }
  if (e) { //если нажата "Сохранить"
    Object.keys(f).map(function(k, index) { //перебор элементов формы
      if (f[k].name == `photo`) {
        //TODO
      } else if (f[k].value) {
        pers[f[k].name] = f[k].value;
      } else {
        pers[f[k].name] = null;
      }
    });
    //отправим JSON
  	$.ajax({
  		url: `/index.php/ac/save_pers`,
  		type: `POST`,
  		data: {
        data: JSON.stringify(pers)
  		},
  		success: function(res) {
        try {
          if (res) {
            for (let k in pers) {
              pers[k] = null;
            }
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
  Object.keys(f).map(function(k, index) { //перебор элементов формы
    if (f[k].name == `card_menu`) { //поставить в карты "Не выбрано"
      f[k].value = 0;
    } else if (f[k].name != `class`) { //обнулить значение всех полей, кроме Класс
      f[k].value = null;
    }
  });
  document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/0.jpg)';
  document.getElementById(`photo_del`).hidden = true;
  document.getElementById(`photo_del`).onclick = function() { return false; };
}
