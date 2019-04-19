import {Card} from '../../classes';

const state = {
    last: null,
    removable: null
}

const getters = {}

const mutations = {
    setLast(state, card) {
        state.last = new Card(card)
    },

    clearLast(state) {
        state.last = null
    },

    setRemovable(state, card) {
        state.removable = card
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
