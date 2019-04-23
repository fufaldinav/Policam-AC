// Initialize Firebase
let config = {
    apiKey: process.env.MIX_FCM_API_KEY,
    authDomain: process.env.MIX_FCM_AUTH_DOMAIN,
    databaseURL: process.env.MIX_FCM_DB_URL,
    projectId: process.env.MIX_FCM_PROJECT_ID,
    storageBucket: process.env.MIX_FCM_STORAGE_BUCKET,
    messagingSenderId: process.env.MIX_FCM_SENDER_ID,
}
firebase.initializeApp(config)
// пользователь уже разрешил получение уведомлений
// подписываем на уведомления если ещё не подписали
if (Notification.permission === `granted`) {
    subscribe()
}

window.subscribe = function () {
    let messaging = firebase.messaging()
    messaging.usePublicVapidKey(process.env.MIX_FCM_PUBLIC_VAPID_KEY)
    // запрашиваем разрешение на получение уведомлений
    messaging.requestPermission().then(function () {
        // получаем ID устройства
        messaging.getToken().then(function (currentToken) {
            console.log(`Токен успешно получен`) //TODO перевод
            if (currentToken) {
                sendTokenToServer(currentToken)
            } else {
                console.warn(`Не удалось получить токен.`) //TODO перевод
                setTokenSentToServer(false)
            }
        }).catch(function (err) {
            console.warn(`При получении токена произошла ошибка.`, err) //TODO перевод
            setTokenSentToServer(false)
        })
    }).catch(function (err) {
        console.warn(`Не удалось получить разрешение на показ уведомлений.`, err) //TODO перевод
    })
}

// отправка ID на сервер
function sendTokenToServer(currentToken) {
    if (!isTokenSentToServer(currentToken)) {
        console.log(`Отправка токена на сервер...`) //TODO перевод
        axios.post(`/users/token`, {
                token: currentToken
            })
            .then(function (response) {
                console.log(response.data)
            })
            .catch(function (error) {
                console.log(error)
            })
        setTokenSentToServer(currentToken)
    } else {
        console.log(`Токен уже отправлен на сервер.`) //TODO перевод
    }
}

// используем localStorage для отметки того,
// что пользователь уже подписался на уведомления
function isTokenSentToServer(currentToken) {
    return window.localStorage.getItem(`sentFirebaseMessagingToken`) == currentToken
}

function setTokenSentToServer(currentToken) {
    window.localStorage.setItem(
        `sentFirebaseMessagingToken`,
        currentToken ? currentToken : ``
    )
}
