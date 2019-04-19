const state = {
    id: '#ac-form-modal',
    title: '',
    message: '',
    shown: false,
    acceptButton: null
}

const getters = {}

const mutations = {
    setTitle(state, title) {
        state.title = title
    },

    setMessage(state, message) {
        state.message = message
    },

    setAcceptButton(state, buttonType) {
        state.acceptButton = buttonType
    },

    setShown(state) {
        state.shown = true
    },

    setHidden(state) {
        state.shown = false
    }
}

const actions = {
    show({state}) {
        if (state.shown === false) {
            $(state.id).modal('show')
        }
    },

    close({state}) {
        if (state.shown === true) {
            $(state.id).modal('hide')
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
