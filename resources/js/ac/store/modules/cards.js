const state = {
    last: {id: 0, wiegand: '000000000000'}
}

const getters = {

}

const mutations = {
    setLast(state, card) {
        state.last = {id: card.id, wiegand: card.wiegand};
    }
}

const actions = {

}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
