// Initialize Firebase
let config = {
	apiKey: `AIzaSyDI_-AwpqcTclSXCyXgYJzvaTNC-dky9iY`,
	authDomain: `policam-ac.firebaseapp.com`,
	databaseURL: `https://policam-ac.firebaseio.com`,
	projectId: `policam-ac`,
	storageBucket: `policam-ac.appspot.com`,
	messagingSenderId: `1005476478589`
};
firebase.initializeApp(config);
// пользователь уже разрешил получение уведомлений
// подписываем на уведомления если ещё не подписали
if (Notification.permission === `granted`) {
	subscribe();
}

function subscribe() {
	let messaging = firebase.messaging();
	messaging.usePublicVapidKey(`BPKQjI8lJAE9pymLNyKm5fsJSsu-7vXlPZivaRvR52lxGWgsxF2TN5s_iaIKQ1LWNZPh0S8arKNOXfq9nAAB3Yg`);
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
		$.ajax({
			url: `[ci_site_url]users/token`,
			type: `POST`,
			data: {
				token: currentToken
			},
			success: function(res) {
				console.log(res);
			},
			error: function() {
				console.warn(`Неизвестная ошибка`); //TODO перевод
			}
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
