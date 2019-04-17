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
import AcAlert from "./ac/components/AcAlert";

const divisions = {
    namespaced: true,
    state: {
        collection: {},
        selected: null
    },
    mutations: {
        add(state, division) {
            Vue.set(state.collection, division.id, new Division(division));
        },
        addPerson(state, relation) {
            let index = state.collection[relation.divisionId].persons.indexOf(relation.personId);
            if (index === -1) {
                state.collection[relation.divisionId].persons.push(relation.personId);
            }
        },
        removePerson(state, relation) {
            let index = state.collection[relation.divisionId].persons.indexOf(relation.personId);
            state.collection[relation.divisionId].persons.splice(index, 1);
        },
        setSelected(state, division) {
            if (division === undefined) {
                state.selected = null;
            } else {
                state.selected = division;
            }
        }
    }
}

const persons = {
    namespaced: true,
    state: {
        collection: {},
        selected: new Person({})
    },
    mutations: {
        add(state, person) {
            Vue.set(state.collection, person.id, new Person(person));
        },
        update(state, person) {
            Vue.set(state.collection, person.id, person);
        },
        remove(state, person) {
            Vue.delete(state.collection, person.id);
        },
        setSelected(state, person) {
            if (person === undefined) {
                state.selected = new Person({});
            } else {
                state.selected = person;
            }
        }
    },
    actions: {
        add({state, commit}, person) {
            if (state.collection.hasOwnProperty(person.id)) {
                state.collection[person.id].divisions = [...state.collection[person.id].divisions, ...person.divisions];
            } else {
                commit('add', person);
            }
        },
        async saveSelected({state, commit}) {
            window.axios.post('/api/persons', {
                person: state.selected
            }).then(function (response) {
                let person = response.data;
                let divisions = [];

                for (let division of person.divisions) {
                    let id = division.id;
                    divisions.push(id);
                    commit('divisions/addPerson', {divisionId: id, personId: person.id}, {root: true});
                }

                person.divisions = divisions;

                commit('add', person);
                commit('setSelected');

                window.Ac.alert(person.f + ' ' + person.i + ' ' + i18n.t('ac.saved') + ' ' + i18n.t('ac.successful'));
            }).catch(function (error) {
                console.log(error);
                window.Ac.alert(error, 'danger');
            });
        },
        async updateSelected({state, commit}) {
            window.axios.put('/api/persons/' + state.selected.id, {
                person: state.selected
            }).then(function (response) {
                let person = response.data;
                let divisions = [];

                for (let division of person.divisions) {
                    let id = division.id;
                    divisions.push(id);
                    commit('divisions/addPerson', {divisionId: id, personId: person.id}, {root: true});
                }

                person.divisions = divisions;

                commit('update', new Person(person));
                commit('setSelected');

                window.Ac.alert(person.f + ' ' + person.i + ' ' + i18n.t('ac.updated') + ' ' + i18n.t('ac.successful'));
            }).catch(function (error) {
                console.log(error);
                window.Ac.alert(error, 'danger');
            });
        },
        async removeSelected({state, commit}) {
            axios.delete('/api/persons/' + state.selected.id).then(function (response) {
                let id = response.data;
                let person = state.collection[id];

                for (let division of person.divisions) {
                    commit('divisions/removePerson', {divisionId: division, personId: person.id}, {root: true});
                }

                let fullName = person.f + ' ' + person.i;

                commit('remove', person);
                commit('setSelected');

                window.Ac.alert(fullName + ' ' + i18n.t('ac.deleted') + ' ' + i18n.t('ac.successful'));
            }).catch(function (error) {
                console.log(error);
                window.Ac.alert(error, 'danger');
            });
        }
    }
}

const store = new Vuex.Store({
    state: {
        loading: true
    },
    actions: {
        async loadDivisions({commit, dispatch}) {
            window.axios.get('/api/divisions').then(function (response) {
                for (let division of response.data) {
                    for (let person of division.persons) {
                        person.divisions = [division.id];
                        dispatch('persons/add', person);
                    }
                    commit('divisions/add', division);
                }
                commit('changeLoadingState', false);
            }).catch(function (error) {
                console.log(error);
                setTimeout(dispatch.loadDivisions, 2000); //TODO перезапуск при ошибке
            });
        }
    },
    mutations: {
        changeLoadingState(state, loading) {
            state.loading = loading;
        }
    },
    modules: {
        divisions: divisions,
        persons: persons
    }
});

window.Ac = new Vue({
    el: '#ac',
    store,
    i18n,
    components: {AcMenuLeft, AcMenuRight, AcFormPerson, AcAlert},
    data: {
        alertMessage: null,
        alertType: null
    },
    computed: Vuex.mapState({
        loading: state => state.loading,
        divisions: state => state.divisions.collection, //TODO delete
        persons: state => state.persons.collection //TODO delete
    }),
    created() {
        store.dispatch('loadDivisions');
    },
    methods: {
        alert(message, type) {
            if (type === undefined) {
                type = 'alert-info';
            } else {
                type = 'alert-' + type;
            }

            this.alertMessage = message;
            this.alertType = type;

            setTimeout(this.closeAlert, 5000);
        },
        closeAlert() {
            this.alertMessage = null;
        }
    }
});
