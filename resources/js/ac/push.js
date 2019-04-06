// Initialize Firebase
let config = {
    apiKey: `{{ config('ac.fcm.api_key') }}`,
    authDomain: `{{ config('ac.fcm.auth_domain') }}`,
    databaseURL: `{{ config('ac.fcm.database_url') }}`,
    projectId: `{{ config('ac.fcm.project_id') }}`,
    storageBucket: `{{ config('ac.fcm.storage_bucket') }}`,
    messagingSenderId: `{{ config('ac.fcm.messaging_sender_id') }}`
};
firebase.initializeApp(config);
// пользователь уже разрешил получение уведомлений
// подписываем на уведомления если ещё не подписали
if (Notification.permission === `granted`) {
    subscribe();
}

window.subscribe = function () {
    let messaging = firebase.messaging();
    messaging.usePublicVapidKey(`{{ config('ac.fcm.public_vapid_key') }}`);
    // запрашиваем разрешение на получение уведомлений
    messaging.requestPermission().then(function () {
        // получаем ID устройства
        messaging.getToken().then(function (currentToken) {
            console.log(`Токен успешно получен`); //TODO перевод
            if (currentToken) {
                sendTokenToServer(currentToken);
            } else {
                console.warn(`Не удалось получить токен.`); //TODO перевод
                setTokenSentToServer(false);
            }
        }).catch(function (err) {
            console.warn(`При получении токена произошла ошибка.`, err); //TODO перевод
            setTokenSentToServer(false);
        });
    }).catch(function (err) {
        console.warn(`Не удалось получить разрешение на показ уведомлений.`, err); //TODO перевод
    });
}

// отправка ID на сервер
function sendTokenToServer(currentToken) {
    if (!isTokenSentToServer(currentToken)) {
        console.log(`Отправка токена на сервер...`); //TODO перевод
        axios.post(process.env.MIX_APP_URL + `/users/token`, {
                token: currentToken
            })
            .then(function (response) {
                console.log(response.data);
            })
            .catch(function (error) {
                console.log(error);
            });
        setTokenSentToServer(currentToken);
    } else {
        console.log(`Токен уже отправлен на сервер.`); //TODO перевод
    }
}

// используем localStorage для отметки того,
// что пользователь уже подписался на уведомления
function isTokenSentToServer(currentToken) {
    return window.localStorage.getItem(`sentFirebaseMessagingToken`) == currentToken;
}

function setTokenSentToServer(currentToken) {
    window.localStorage.setItem(
        `sentFirebaseMessagingToken`,
        currentToken ? currentToken : ``
    );
}
