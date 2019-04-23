import {Division} from '../../classes'

const state = {
    collection: [],
    selected: null
}

const getters = {
    getById: state => id => {
        return state.collection.find((value) => {
            if (value.id === id) return true
        })
    },

    sorted: state => {
        function naturalCompare(a, b) {
            let ax = [], bx = []

            a.replace(/(\d+)|(\D+)/g, function (_, $1, $2) {
                ax.push([$1 || Infinity, $2 || ''])
            })
            b.replace(/(\d+)|(\D+)/g, function (_, $1, $2) {
                bx.push([$1 || Infinity, $2 || ''])
            })

            while (ax.length && bx.length) {
                let an = ax.shift()
                let bn = bx.shift()
                let nn = (an[0] - bn[0]) || an[1].localeCompare(bn[1])
                if (nn) return nn
            }

            return ax.length - bx.length
        }

        return state.collection.sort((a, b) => {
            if (a.type < b.type) return -1
            if (a.type > b.type) return 1
            let sortByName = naturalCompare(a.name, b.name)
            if (sortByName !== 0) return sortByName
            return 0
        })
    },

    selectedSortedPersons: (state, getters, rootState) => {
        if (state.selected === null) return null

        return state.selected.persons.sort((a, b) => {
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
    }
}

const mutations = {
    add(state, division) {
        state.collection.push(new Division(division))
    },

    clearCollection(state) {
        while (state.collection.length > 0) {
            state.collection.pop()
        }
    },

    addPerson(state, relation) {
        let division = state.collection.find((value) => {
            if (value.id === relation.divisionId) return true
        })
        let index = division.persons.indexOf(relation.personId)
        if (index === -1) {
            division.persons.push(relation.personId)
        }
    },

    removePerson(state, relation) {
        let division = state.collection.find((value) => {
            if (value.id === relation.divisionId) return true
        })
        let index = division.persons.indexOf(relation.personId)
        if (index > -1) {
            division.persons.splice(index, 1)
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
