import Vue from 'vue'
import {Division} from '../../classes'

const state = {
    collection: {},
    selected: null
}

const getters = {
    selectedSortedPersons: (state, getters, rootState) => {
        if (state.selected === null) return null
        state.selected.persons.sort((a, b) => {
            let personA = rootState.persons.collection[a]
            let personB = rootState.persons.collection[b]
            if (personA.f < personB.f) return -1
            if (personA.f > personB.f) return 1
            if (personA.i < personB.i) return -1
            if (personA.i > personB.i) return 1
            if (personA.o < personB.o) return -1
            if (personA.o > personB.o) return 1
            return 0
        })
        return state.selected.persons
    }
}

const mutations = {
    add(state, division) {
        Vue.set(state.collection, division.id, new Division(division))
    },

    addPerson(state, relation) {
        let index = state.collection[relation.divisionId].persons.indexOf(relation.personId)
        if (index === -1) {
            state.collection[relation.divisionId].persons.push(relation.personId)
        }
    },

    removePerson(state, relation) {
        let index = state.collection[relation.divisionId].persons.indexOf(relation.personId)
        if (index > -1) {
            state.collection[relation.divisionId].persons.splice(index, 1)
        }
    },

    setSelected(state, division) {
        state.selected = division
    },

    clearSelected(state) {
        state.selected = null
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
