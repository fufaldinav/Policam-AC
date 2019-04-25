/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap')

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

window.Pusher = require('pusher-js')

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

import Vue from 'vue'
import i18n from './vue-i18n'
import store from './ac/store'

import AcLayout from './ac/components/AcLayout'
import AcNavBar from './ac/components/AcNavBar'
import AcObserver from './ac/components/observer/AcObserver'
import AcCpFunctionCard from './ac/components/cp/AcCpFunctionCard'
import AcCpPersons from './ac/components/cp/persons/AcCpPersons'
import AcCpStudents from './ac/components/cp/students/AcCpStudents'
import AcOrganizationsDropdownMenu from './ac/components/AcOrganizationsDropdownMenu'
import AcAlert from './ac/components/AcAlert'

import Vue2TouchEvents from 'vue2-touch-events'

Vue.use(Vue2TouchEvents)

Object.defineProperty(Vue.prototype, '$bus', {
    get() {
        return this.$root.bus;
    }
});

window.Ac = new Vue({
    el: '#ac',
    store,
    i18n,

    components: {
        AcLayout, AcNavBar, AcObserver, AcCpFunctionCard,
        AcCpStudents, AcCpPersons, AcOrganizationsDropdownMenu, AcAlert
    },

    data: {
        bus: new Vue({}),
        alertMessage: null,
        alertType: null
    },

    computed: {
        divisions() {
            return store.state.divisions.collection
        }
    },

    created() {
        window.addEventListener('resize', this.handleResize)
        this.handleResize()
    },

    destroyed() {
        window.removeEventListener('resize', this.handleResize)
    },

    methods: {
        alert(message, type = 'info') {
            type = 'alert-' + type

            this.alertMessage = message
            this.alertType = type

            setTimeout(this.closeAlert, 5000)
        },

        closeAlert() {
            this.alertMessage = null
        },

        handleResize() {
            store.dispatch('bp/handleResize', window.innerWidth)
        }
    }
});
