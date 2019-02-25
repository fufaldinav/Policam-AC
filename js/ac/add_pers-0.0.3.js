let events = [2, 3]; //где 2,3 - события запрещенного входа/выхода
let pers =  {
              'f': null,
              'i': null,
              'o': null,
              'photo': null,
              'class': null,
              'birthday': null,
              'address': null,
              'phone': null,
              'card': null
            }
//сохранение пользователя в БД
function savePersInfo() {
  let checkValidity = true;
  Object.keys(pers).map(function(k, index) {
    let elem = document.getElementById(k);
    if (elem.required && elem.value === ``) {
      elem.classList.add(`no-data`);
      checkValidity = false;
    }
    if (k == `photo`) {
      //TODO
    } else if (elem.value) {
      pers[k] = elem.value;
    } else {
      pers[k] = null;
    }
  });
  if (!checkValidity) {
    alert(`Введены не все данные`);
  } else {
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
            clearPersInfo();
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
}
function clearPersInfo() {
  document.getElementById(`card`).value = 0; //поставить в карты "Не выбрано"
  Object.keys(pers).map(function(k, index) {
    let elem = document.getElementById(k);
    if (k != `class`) { //обнулить значение всех полей, кроме Класс
      pers[k] = null;
      elem.value = null;
    }
  });
  document.getElementById(`photo_bg`).style.backgroundImage = 'url(/img/ac/s/0.jpg)';
  document.getElementById(`photo_del`).hidden = true;
  document.getElementById(`photo_del`).onclick = function() { return false; };
}
function checkData(e) {
  e.classList.remove(`no-data`);
}
