import {Card} from '../../classes'

const state = {
    last: null,
    manualInput: false,
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

    setManualInput(state, status = true) {
        state.manualInput = status
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
