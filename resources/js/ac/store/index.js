import Vue from 'vue';
import Vuex from 'vuex';
import cards from './modules/cards';
import divisions from './modules/divisions';
import loader from './modules/loader';
import modal from './modules/modal';
import persons from './modules/persons';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        cards,
        divisions,
        loader,
        modal,
        persons
    }
});
