import Vue from 'vue'
import {Event} from '../../classes'

const state = {
    collection: {},
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
        Vue.set(state.collection, event.id, new Event(event))
    },

    remove(state, event) {
        Vue.delete(state.collection, event.id)
    },

    clearCollection(state) {
        for (let event in state.collection) {
            Vue.delete(state.collection, event.id)
        }
    }
}

const actions = {
    async getLast({commit, rootState}) {
        let organizationId = rootState.organizations.selected.id;
        window.axios.get('/api/events/' + organizationId + '/50')
            .then(response => {
                for (let event of response.data) {
                    commit('add', event);
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
