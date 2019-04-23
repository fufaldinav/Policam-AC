const state = {
    leftMenuShown: true
}

const getters = {}

const mutations = {
    showLeftMenu(state) {
        state.leftMenuShown = true
    },

    showForm(state) {
        state.leftMenuShown = false
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
