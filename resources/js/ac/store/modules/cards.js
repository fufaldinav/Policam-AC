import {Card} from '../../classes';

const state = {
    last: null
}

const getters = {}

const mutations = {
    setLast(state, card) {
        state.last = new Card(card);
    },
    clearLast(state) {
        state.last = null;
    }
}

const actions = {}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
