import Vue from 'vue'
import Vuex from 'vuex'

import actions from './actions'
import mutations from './mutations'

import bp from './modules/breakpoint'
import cards from './modules/cards'
import cp from './modules/cp'
import divisions from './modules/divisions'
import history from './modules/history'
import loader from './modules/loader'
import messenger from './modules/messenger'
import modal from './modules/modal'
import organizations from './modules/organizations'
import persons from './modules/persons'
import postreg from './modules/postreg'
import timetable from './modules/timetable'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        debug: process.env.NODE_ENV === 'development',
        personsMustBeLoaded: 1,
        userRole: null
    },

    actions,
    mutations,

    modules: {
        bp,
        cards,
        cp,
        divisions,
        history,
        loader,
        modal,
        messenger,
        organizations,
        persons,
        postreg,
        timetable
    }
})
