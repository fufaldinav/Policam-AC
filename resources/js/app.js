/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     authEndpoint : '/broadcasting/auth',
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     wsHost: window.location.hostname,
//     wsPort: process.env.MIX_WS_PORT,
//     wssPort: process.env.MIX_WS_PORT,
//     disableStats: true,
// });

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

/**
 * Блок локализации
 */

import VueInternationalization from 'vue-i18n';
import Locale from './vue-i18n-locales.generated';

Vue.use(VueInternationalization);

const lang = document.documentElement.lang.substr(0, 2);

const i18n = new VueInternationalization({
    locale: lang,
    messages: Locale
});


import {Division, Person} from "./ac/classes";
import AcMenuLeft from "./ac/components/AcMenuLeft";
import AcMenuRight from "./ac/components/AcMenuRight";
import AcFormPerson from "./ac/components/AcFormPerson";

window.Ac = new Vue({
    el: '#ac',
    i18n,
    components: {
        AcMenuLeft,
        AcMenuRight,
        AcFormPerson
    },
    data: {
        currentDivision: null,
        divisions: {},
        persons: {}
    },
    methods: {
      setCurrentDivision: function (id) {
          this.currentDivision = id;
      }
    },
    created: function () {
        let data = window.AcData;

        if (data['divisions'] !== undefined) {
            for (let k in data['divisions']) {
                this.divisions[k] = new Division(data['divisions'][k]);
            }
        }
        if (data['persons'] !== undefined) {
            for (let k in data['persons']) {
                this.persons[k] = new Person(data['persons'][k]);
            }
        }

        delete window.AcData;
    }
});
