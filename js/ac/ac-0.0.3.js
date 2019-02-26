let time, events = [4,5]; //где 4,5 - события разрешенного входа/выхода

document.addEventListener("DOMContentLoaded", function() {
  time = getServerTime();
  getNewMsgs(events, time);
});

//получение времени от сервера
function getServerTime() {
  $.ajax({
    url: '/index.php/util/get_time',
    success: function(data) {
      time = data;
    },
    type: 'GET'
  });
}

//получение сообщений от сервера
function getNewMsgs(events, time) {
  $.ajax({
    url: '/index.php/util/get_events',
    type: 'POST',
    data: {
      events: events,
      time: time
    },
    success: function(data) {
      try {
        data = JSON.parse(data);
        time = data.time;
        if (data.msgs.length > 0) {
          let card = data.msgs[data.msgs.length - 1].card_id; //последний прочитанный ключ из БД
          setPersData(card);
        }
        setTimeout(function() {
          getNewMsgs(events, time);
        }, 10);
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
function setPersData(card) {
  $.ajax({
    url: '/index.php/db/get_pers',
    type: 'POST',
    data: {
      card: card
    },
    success: function(data) {
      try {
        data = JSON.parse(data);
        if (data) {
          Object.keys(data).map(function(k) { //перебор полученных данных
            if (document.getElementById(k)) {
              document.getElementById(k).value = data[k];
            }
          });
          let photo = document.getElementById(`photo_bg`);
          if (!data.photo) {
            data.photo = `0`;
          }
          photo.style.backgroundImage = 'url(/img/ac/s/' + data.photo + '.jpg)';
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

//переключение меню
function toggleMenu() {
  let menu = document.getElementById(`menu`);
  let main = document.getElementById(`main`);
  if (menu.hidden) {
    getClasses(menu);
    main.style.marginLeft = `22%`;
    menu.hidden = false;
  } else {
    main.style.marginLeft = `2%`;
    menu.hidden = true;
  }
}

function getClasses(menu) {
  $.ajax({
    url: '/index.php/db/get_classes',
    type: 'GET',
    success: function(data) {
      try {
        data = JSON.parse(data);
        if (data) {
          let classList = ``;
          data.forEach(function(c) {
            classList += `<button id="class${c.id}" type="button" onclick="">${c.number} "${c.letter}"</button><br />`;
          });
          menu.innerHTML = classList;
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
