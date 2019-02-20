let persInfo, time, events = [4,5]; //где 4,5 - события разрешенного входа/выхода

document.addEventListener("DOMContentLoaded", function() {
  persInfo = document.forms.pers_info;
  time = getServerTime();
  getNewMsgs(events, time);
});

//получение времени от сервера
function getServerTime() {
  $.ajax({
    url: '/index.php/ac/get_time',
    success: function(data) {
      time = data;
    },
    type: 'GET'
  });
}

//получение сообщений от сервера
function getNewMsgs(events, time) {
  $.ajax({
    url: '/index.php/ac/get_events',
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
    url: '/index.php/ac/get_pers',
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
