import {Event} from '../../classes'

const state = {
    collection: [],
    last: new Event({}),
    types: {
        2: 'Доступ запрещен',
        3: 'Доступ запрещен',
        4: 'Вход разрешен',
        5: 'Выход разрешен',
        6: 'Доступ запрещен',
        7: 'Доступ запрещен',
        16: 'Вход состоялся',
        17: 'Выход состоялся'
    }
}

const getters = {
    getAll: state => {
        return state.collection
    },

    getType: state => id => {
        return state.types[id]
    }
}

const mutations = {
    add(state, event) {
        state.collection.unshift(new Event(event))
        while (state.collection.length > 5) {
            state.collection.pop()
        }
    },

    remove(state, event) {
        let index = state.collection.indexOf(event)
        if (index > -1) {
            state.collection.splice(index, 1)
        }
    },

    clearCollection(state) {
        while (state.collection.length > 0) {
            state.collection.pop()
        }
    }
}

const actions = {
    async getLast({commit, rootState}, orgId) {
        window.axios.get('/api/events/' + orgId + '/5')
            .then(response => {
                for (let i = response.data.length; i--; i >= 0) {
                    commit('add', response.data[i]);
                }
            })
            .catch(error => {
                if (rootState.debug) console.log(error)
            })
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
