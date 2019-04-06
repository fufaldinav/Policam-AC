/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app'
// });

axios.get(process.env.MIX_APP_URL + '/controllers/get_list')
    .then(function (response) {
        for (let k in response.data) {
            Echo.private(`controller-events.${response.data[k].id}`)
                .listen('EventReceived', (e) => {
                    if (!events.includes(e.event)) {
                        return;
                    }
                    if (e.event == 16 || e.event == 17) {
                        setPersonInfo(e.card_id);
                    } else if (e.event == 2 || e.event == 3) {
                        if (!document.getElementById(`cards`).disabled) { //если меню неизвестных карт активно
                            let o = confirm(`Введен неизвестный ключ. Выбрать его в качестве нового ключа пользователя?`); //TODO перевод
                            if (o) {
                                getCards(e.card_id);
                            }
                        } else if (document.getElementById(`unknown_cards`).hidden) {
                            let o = confirm(`Введен неизвестный ключ. Добавить его текущему пользователю?`); //TODO перевод
                            if (o) {
                                saveCard(e.card_id);
                            }
                        }
                    }
                })
                .listen('ControllerConnected', (e) => {
                    SetControllerStatus(e.controller_id);
                });
        }
    })
    .catch(function (error) {
        console.log(error);
    });

window.SetControllerStatus = function (controller_id) {
    console.log(controller_id);
}

window.trans = function (key, replace = {})
{
    let translation = key.split('.').reduce((t, i) => t[i] || null, window.translations);

    for (let placeholder in replace) {
        translation = translation.replace(`:${placeholder}`, replace[placeholder]);
    }

    return translation;
}

window.trans_choice = function (key, count = 1, replace = {})
{
    let translation = key.split('.').reduce((t, i) => t[i] || null, window.translations).split('|');

    translation = count > 1 ? translation[1] : translation[0];

    for (let placeholder in replace) {
        translation = translation.replace(`:${placeholder}`, replace[placeholder]);
    }

    return translation;
}

require('./ac/add_person');
require('./ac/classes');
require('./ac/edit_persons');
require('./ac/main');
require('./ac/observer');
require('./ac/push');
