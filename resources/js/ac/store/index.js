import Vue from 'vue'
import Vuex from 'vuex'

import actions from './actions'
import mutations from './mutations'

import bp from './modules/breakpoint'
import cards from './modules/cards'
import cp from './modules/cp'
import divisions from './modules/divisions'
import loader from './modules/loader'
import modal from './modules/modal'
import persons from './modules/persons'

Vue.use(Vuex);

export default new Vuex.Store({
    actions,
    mutations,

    modules: {
        bp,
        cards,
        cp,
        divisions,
        loader,
        modal,
        persons
    }
});
