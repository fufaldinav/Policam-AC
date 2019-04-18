import Vue from 'vue';
import Vuex from 'vuex';
import divisions from './modules/divisions';
import persons from './modules/persons';
import loader from './modules/loader';
import cards from './modules/cards';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        divisions,
        persons,
        loader,
        cards
    }
});
