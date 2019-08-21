import {Division} from '../../classes'
import i18n from "../../../vue-i18n";

const state = {
    collection: [],
    selected: null,
    numberRule: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
    letterRule: ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'И']
}

const getters = {
    getById: state => id => {
        return state.collection.find(value => value.id === id)
    },

    findName: state => name => {
        let division = state.collection.find(division => division.name === name)
        return division !== undefined
    },

    checkNumberRule: state => number => {
        let index = state.numberRule.indexOf(parseInt(number))
        return index > -1
    },

    checkLetterRule: state => letter => {
        let index = state.letterRule.indexOf(letter)
        return index > -1
    },

    checkName: (state, getters) => name => {
        let splittedName = name.split(' ', 2)
        let number = splittedName[0]
        let letter = ''

        if (splittedName.length > 1) {
            letter = splittedName[1].replace(/"/g, '')
        } else {
            return false
        }

        return number !== null && number !== '' && letter !== '' && ! getters.findName(name) && getters.checkNumberRule(number) && getters.checkLetterRule(letter)
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

    update(state, division) {
        let id = division.id
        let oldDivision = state.collection.find(oldDivision => oldDivision.id === id)
        let index = state.collection.indexOf(oldDivision)
        if (index > -1) {
            state.collection.splice(index, 1)
        }
        state.collection.push(new Division(division))
    },

    remove(state, division) {
        let index = state.collection.indexOf(division)
        if (index > -1) {
            state.collection.splice(index, 1)
        }
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
        if (division === undefined) return
        let index = division.persons.indexOf(relation.personId)
        if (index === -1) {
            division.persons.push(relation.personId)
        }
    },

    removePerson(state, relation) {
        let division = state.collection.find((value) => {
            if (value.id === relation.divisionId) return true
        })
        if (division === undefined) return
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

const actions = {
    async saveSelected({state, commit, rootState}) {
        window.axios.post('/api/divisions', {
            division: state.selected
        })
            .then(response => {
                let division = response.data

                commit('add', division)

                commit('clearSelected')

                window.Ac.alert(division.name + ' ' + i18n.t('сохранено') + ' ' + i18n.t('успешно'))
            })
            .catch(error => {
                if (rootState.debug) console.log(error)
                window.Ac.alert(error, 'danger')
            })
    },

    async removeSelected({state, commit, rootState, rootGetters}) {
        window.axios.delete('/api/divisions/' + state.selected.id)
            .then(response => {
                let id = response.data
                let division = rootGetters['divisions/getById'](id)

                let name = division.name

                commit('remove', division)

                commit('clearSelected')

                window.Ac.alert(name + ' ' + i18n.t('удалено') + ' ' + i18n.t('успешно'))
            })
            .catch(error => {
                if (rootState.debug) console.log(error)
                window.Ac.alert(error, 'danger')
            })
    },

    async updateSelected({state, commit, rootState}) {
        window.axios.post('/api/divisions/' + state.selected.id, {
            division: state.selected
        })
            .then(response => {
                let division = response.data

                commit('update', division)

                commit('clearSelected')

                window.Ac.alert(`${division.name} сохранено успешно`)
            })
            .catch(error => {
                if (rootState.debug) console.log(error)
                window.Ac.alert(error, 'danger')
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
