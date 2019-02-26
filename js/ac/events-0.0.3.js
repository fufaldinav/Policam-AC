let time;

document.addEventListener("DOMContentLoaded", function() {
  time = getServerTime();
  getNewMsgs(events, time);
});

//получение времени от сервера
function getServerTime() {
  $.ajax({
    url: `/index.php/util/get_time`,
    type: `GET`,
    success: function(data) {
      try {
        time = data;
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

//получение сообщений из БД
function getNewMsgs(events, time) {
  $.ajax({
    url: `/index.php/util/get_events`,
    type: `POST`,
    data: {
      events: events,
      time: time
    },
    success: function(data) {
      try {
        data = JSON.parse(data);
        time = data.time;
        if (!document.getElementById(`card`).disabled) { //если меню неизвестных карт активно
          if (data.msgs.length > 0) {
            let o = confirm(`Введен неизвестный ключ. Выбрать его в качестве нового ключа пользователя?`);
            if (o) {
              let card = data.msgs[data.msgs.length - 1].card_id; //последний прочитанный ключ из БД
              getCards(card);
            }
          }
        } else if (document.getElementById(`card_selector`).hidden) {
          if (data.msgs.length > 0) {
            let o = confirm(`Введен неизвестный ключ. Добавить его текущему пользователю?`);
            if (o) {
              let card = data.msgs[data.msgs.length - 1].card_id;  //последний прочитанный ключ из БД
              saveCard(card);
            }
          }
        }
        setTimeout(function() {
          getNewMsgs(events, time);
        }, 100);
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
