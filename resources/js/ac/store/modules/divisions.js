import Vue from 'vue';
import {Division} from "../../classes";

const state = {
    collection: {},
    selected: null
}

const getters = {}

const mutations = {
    add(state, division) {
        Vue.set(state.collection, division.id, new Division(division));
    },

    addPerson(state, relation) {
        let index = state.collection[relation.divisionId].persons.indexOf(relation.personId);
        if (index === -1) {
            state.collection[relation.divisionId].persons.push(relation.personId);
        }
    },

    removePerson(state, relation) {
        let index = state.collection[relation.divisionId].persons.indexOf(relation.personId);
        if (index > -1) {
            state.collection[relation.divisionId].persons.splice(index, 1);
        }
    },

    setSelected(state, division) {
        if (division === undefined) {
            state.selected = null;
        } else {
            state.selected = division;
        }
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
