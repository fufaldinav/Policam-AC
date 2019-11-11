const state = {
    history: [],
    last: null
}

const getters = {}

const mutations = {
    add: state => message => {
        state.history.push(message)
        while (state.history.length > 25) {
            state.history.shift()
        }
        state.last = message
    },

    setLastMessage: state => message => {
        state.last = message
    }
}

const actions = {
    subscribe({commit, rootState}) {
        for (let controller of rootState.organizations.selected.controllers) {
            window.Echo.private('controller-events.' + controller)
                .listen('EventReceived', e => {
                    commit('add', e)
                    window.Ac.$bus.$emit('EventReceived', e)
                })
                .listen('ControllerConnected', e => {
                    commit('add', e)
                    window.Ac.$bus.$emit('ControllerConnected', e)
                })
                .listen('PingReceived', e => {
                    commit('add', e)
                    window.Ac.$bus.$emit('PingReceived', e)
                })
        }
    },

    unsubscribe({rootState}) {
        for (let controller of rootState.organizations.selected.controllers) {
            window.Echo.leave('controller-events.' + controller)
        }
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
