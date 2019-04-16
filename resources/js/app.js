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

window.Pusher = require('pusher-js');

// import Echo from 'laravel-echo'

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

const Vuex = require('vuex');
Vue.use(Vuex);

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

const store = new Vuex.Store({
    state: {
        divisions: {},
        persons: {},
        loading: true
    },
    actions: {
        async loadDivisions({commit}) {
            window.axios.get('/api/divisions')
                .then(function (response) {
                    let divisions = response.data;
                    for (let k in divisions) {
                        for (let l in divisions[k].persons) {
                            commit('addPerson', divisions[k].persons[l]);
                        }
                        commit('addDivision', divisions[k]);
                    }
                    commit('changeLoadingState', false);
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    },
    mutations: {
        addDivision(state, division) {
            state.divisions[division.id] = new Division(division);
        },
        addPerson(state, person) {
            state.persons[person.id] = new Person(person);
        },
        updatePerson(state, person) {
            state.persons[person.id] = person;
        },
        changeLoadingState(state, loading) {
            state.loading = loading;
        }
    },
    getters: {
        personsCount: state => id => {
            return state.divisions[id].persons().length;
        },
        divisions: state => {
            return state.divisions;
        },
        persons: state => {
            return state.persons;
        },
        division: state => id => {
            return state.divisions[id];
        },
        person: state => id => {
            return state.persons[id];
        }
    },
    modules: {}
});

window.Ac = new Vue({
    el: '#ac',
    store,
    i18n,
    components: {
        AcMenuLeft,
        AcMenuRight,
        AcFormPerson
    },
    data: {
        currentDivision: null
    },
    computed: Vuex.mapState(['loading']),
    created() {
        store.dispatch('loadDivisions');
    },
    methods: {
        setCurrentDivision(id) {
            this.currentDivision = id;
        }
    }
});
