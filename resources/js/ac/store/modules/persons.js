import Vue from 'vue'
import i18n from '../../../vue-i18n'
import {Card, Person, Photo} from '../../classes'

const state = {
    collection: {},
    selected: new Person({})
}

const getters = {}

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

    setSelected(state, person) {
        state.selected = person
    },

    clearSelected(state) {
        state.selected = new Person({})
    },

    addCard(state, card) {
        state.selected.cards.push(new Card(card))
    },

    removeCard(state, card) {
        let index = state.selected.cards.indexOf(card)
        if (index > -1) {
            if (state.selected.id > 0) {
                state.selected.cardsToDelete.push(card)
            }
            state.selected.cards.splice(index, 1)
        }
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
    add({state, commit}, person) {
        if (state.collection.hasOwnProperty(person.id)) {
            state.collection[person.id].divisions = [...state.collection[person.id].divisions, ...person.divisions]
        } else {
            commit('add', person)
        }
    },

    async saveSelected({state, commit}) {
        window.axios.post('/api/persons', {
            person: state.selected
        }).then(function (response) {
            let person = response.data
            let divisions = []

            for (let division of person.divisions) {
                let id = division.id
                divisions.push(id)
            }

            person.divisions = divisions

            commit('add', person)

            for (let id of divisions) {
                commit('divisions/addPerson', {divisionId: id, personId: person.id}, {root: true})
            }

            commit('clearSelected')

            window.Ac.alert(person.f + ' ' + person.i + ' ' + i18n.t('ac.saved') + ' ' + i18n.t('ac.successful'))
        }).catch(function (error) {
            console.log(error)
            window.Ac.alert(error, 'danger')
        })
    },

    async updateSelected({state, commit}) {
        window.axios.put('/api/persons/' + state.selected.id, {
            person: state.selected
        }).then(function (response) {
            let person = response.data
            let divisions = []

            for (let division of person.divisions) {
                let id = division.id
                divisions.push(id)
            }

            person.divisions = divisions

            commit('update', new Person(person))

            for (let id of divisions) {
                commit('divisions/addPerson', {divisionId: id, personId: person.id}, {root: true})
            }

            commit('clearSelected')

            window.Ac.alert(person.f + ' ' + person.i + ' ' + i18n.t('ac.updated') + ' ' + i18n.t('ac.successful'))
        }).catch(function (error) {
            console.log(error)
            window.Ac.alert(error, 'danger')
        })
    },

    async removeSelected({state, commit}) {
        axios.delete('/api/persons/' + state.selected.id).then(function (response) {
            let id = response.data
            let person = state.collection[id]

            for (let division of person.divisions) {
                commit('divisions/removePerson', {divisionId: division, personId: person.id}, {root: true})
            }

            let fullName = person.f + ' ' + person.i

            commit('remove', person)
            commit('clearSelected')

            window.Ac.alert(fullName + ' ' + i18n.t('ac.deleted') + ' ' + i18n.t('ac.successful'))
        }).catch(function (error) {
            console.log(error)
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
