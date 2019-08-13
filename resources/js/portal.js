require('./bootstrap')

import Vue from 'vue'
import store from './portal/store'

import Vue2TouchEvents from 'vue2-touch-events'

Vue.use(Vue2TouchEvents)

window.Ac = new Vue({
    el: '#portal',
    store,

    components: {},

    data: {
        bus: new Vue({}),
        alertMessage: null,
        alertType: null
    },

    computed: {},

    created() {
        window.addEventListener('resize', this.handleResize)
        this.handleResize()
    },

    beforeDestroy() {
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
