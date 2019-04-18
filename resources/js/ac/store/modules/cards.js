import {Card} from '../../classes';

const state = {
    last: new Card({id: 0, wiegand: '000000000000'})
}

const getters = {

}

const mutations = {
    setLast(state, card) {
        state.last = new Card(card);
    }
}

const actions = {

}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
