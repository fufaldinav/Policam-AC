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

import Echo from 'laravel-echo'

window.Echo = new Echo({
    authEndpoint: '/broadcasting/auth',
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: process.env.MIX_WS_PORT,
    wssPort: process.env.MIX_WS_PORT,
    disableStats: true,
});

import Vue from 'vue';
import i18n from './vue-i18n';
import store from './ac/store';
import {mapState} from 'vuex';

import AcMenuLeft from "./ac/components/AcMenuLeft";
import AcMenuRight from "./ac/components/AcMenuRight";
import AcFormPerson from "./ac/components/AcFormPerson";
import AcAlert from "./ac/components/AcAlert";

window.Ac = new Vue({
    el: '#ac',
    store,
    i18n,
    components: {AcMenuLeft, AcMenuRight, AcFormPerson, AcAlert},
    data: {
        alertMessage: null,
        alertType: null
    },
    computed: {
        ...mapState({
            loading: state => state.loader.loading,
            divisions: state => state.divisions.collection, //TODO delete
            persons: state => state.persons.collection //TODO delete
        })
    },
    created() {
        store.dispatch('loader/loadDivisions');
    },
    mounted() {
        axios.get('/controllers/get_list').then(function (response) {
            for (let k in response.data) {
                window.Echo.private(`controller-events.${response.data[k].id}`)
                    .listen('EventReceived', (e) => {
                        if (e.event === 2 || e.event === 3) {
                            store.commit('cards/setLast', e.card);
                            console.log(e);
                        } else if (e.event === 4 || e.event === 5) {
                            console.log(e);
                        }
                    })
                    .listen('ControllerConnected', (e) => {
                        console.log(e.controller_id);
                    });
            }
        }).catch(function (error) {
            console.log(error);
        });
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
