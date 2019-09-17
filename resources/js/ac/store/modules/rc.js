import Vue from 'vue'
import {ReferralCode} from '../../classes'

const state = {
    collection: [],
    // manually: false
}

const getters = {
    getById: state => id => {
        return state.collection.find(code => code.id === id)
    },

    getByCode: state => code => {
        return state.collection.find(ref => ref.code === code)
    },
}

const mutations = {
    add(state, rc) {
        let ref = state.collection.find(ref => (ref.id === rc.id))
        if (ref === undefined) {
            state.collection.push(new ReferralCode(rc))
        }
    },

    clearCollection(state) {
        while(state.collection.length > 0) {
            state.collection.pop()
        }
    },

    // setSelected(state, person) {
    //     state.selected = person
    // },
    //
    // clearSelected(state) {
    //     state.selected = new Person({})
    // },
    //
    // setManually(state, status = true) {
    //     state.manually = status
    // },
}

const actions = {
    async getReferral({rootState}, code) {
        return new Promise((resolve, reject) => {
            window.axios.get('/api/referral/checkcode/' + code)
                .then(response => {
                    resolve(response.data)
                })
                .catch(error => {
                    reject(error)
                })
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
