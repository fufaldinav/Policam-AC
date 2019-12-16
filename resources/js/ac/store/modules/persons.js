import Vue from 'vue'
import {Person, Photo} from '../../classes'

const state = {
    collection: {},
    selected: new Person({}),
    manually: false,
    searchByCode: false
}

const getters = {
    getById: state => id => {
        if (state.collection.hasOwnProperty(id))
            return state.collection[id]
        else
            return new Person({})
    }
}

const mutations = {
    add(state, person) {
        Vue.set(state.collection, person.id, new Person(person))
    },

    update(state, person) {
        Vue.set(state.collection, person.id, person)
    },

    remove(state, person) {
        Vue.delete(state.collection, person.id)
    },

    clearCollection(state) {
        for (let person in state.collection) {
            Vue.delete(state.collection, person)
        }
    },

    setSelected(state, person) {
        state.selected = person
    },

    clearSelected(state) {
        state.selected = new Person({})
    },

    setManually(state, status = true) {
        state.manually = status
    },

    setSearchByCode(state, status = true) {
        state.searchByCode = status
    },

    addPhoto(state, photo) {
        state.selected.photos.push(new Photo(photo))
    },

    removePhoto(state, photo) {
        let index = state.selected.photos.indexOf(photo)
        if (index > -1) {
            if (state.selected.id > 0) {
                state.selected.photosToDelete.push(photo)
            }
            state.selected.photos.splice(index, 1)
        }
    },
}

const actions = {
    async saveSelected({state, commit, rootState, rootGetters}) {
        window.axios.post('/api/persons', {
            person: state.selected
        })
            .then(response => {
                let person = response.data

                for (let division of person.divisions) {
                    let div = rootGetters['divisions/getById'](division.id)
                    if (div !== undefined) {
                        person.division = div.id
                        break;
                    }
                }

                commit('add', person)

                commit('divisions/addPerson', {divisionId: person.division, personId: person.id}, {root: true})

                commit('clearSelected')

                window.Ac.alert(`${person.f} ${person.i} сохранен успешно`)
            })
            .catch(error => {
                if (rootState.debug) console.log(error)
                window.Ac.alert(error, 'danger')
            })
    },

    async updateSelected({state, commit, rootState, rootGetters}) {
        window.axios.put('/api/persons/' + state.selected.id, {
            person: state.selected
        })
            .then(response => {
                let person = response.data

                for (let division of person.divisions) {
                    let div = rootGetters['divisions/getById'](division.id)
                    if (div !== undefined) {
                        person.division = div.id
                        break;
                    }
                }

                commit('update', new Person(person))

                let oldDivisions = rootGetters['divisions/getWithPerson'](person.id)

                for (let oldDiv of oldDivisions) {
                    commit('divisions/removePerson', {divisionId: oldDiv.id, personId: person.id}, {root: true})
                }

                commit('divisions/addPerson', {divisionId: person.division, personId: person.id}, {root: true})

                commit('clearSelected')

                window.Ac.alert(`${person.f} ${person.i} сохранен успешно`)
            })
            .catch(error => {
                if (rootState.debug) console.log(error)
                window.Ac.alert(error, 'danger')
            })
    },

    async removeSelected({state, commit, rootState, rootGetters}) {
        window.axios.delete('/api/persons/' + state.selected.id)
            .then(response => {
                let id = response.data
                let person = state.collection[id]

                let oldDivisions = rootGetters['divisions/getWithPerson'](person.id)

                for (let oldDiv of oldDivisions) {
                    commit('divisions/removePerson', {divisionId: oldDiv.id, personId: person.id}, {root: true})
                }

                let fullName = person.f + ' ' + person.i

                commit('remove', person)
                commit('clearSelected')

                window.Ac.alert(`${fullName} удален успешно`)
            })
            .catch(error => {
                if (rootState.debug) console.log(error)
                window.Ac.alert(error, 'danger')
            })
    },

    async searchByCode({state, commit, rootState, rootGetters}, code) {
        window.axios.get('/api/referral/person/' + code)
            .then(response => {
                if (response.data !== 0) {
                    let person = response.data;

                    if (person.organization_id !== rootState.organizations.selected.id) {
                        commit('add', person);
                        commit('divisions/addPerson', {divisionId: rootState.divisions.selected, personId: person.id}, {root: true});
                    }
                }
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
