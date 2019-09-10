import Vue from 'vue'
import Vuex from 'vuex'

import actions from './actions'
import mutations from './mutations'

import bp from  './modules/breakpoint'
import postreg from './modules/postreg'

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
        postreg
    }
})
