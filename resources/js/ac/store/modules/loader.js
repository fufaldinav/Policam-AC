const state = {
    loading: true
}

const getters = {}

const mutations = {
    changeLoadingState(state, status) {
        state.loading = status
    }
}

const actions = {
    async loadOrganizations({commit, dispatch, rootState}) {
        window.axios.get('/users/organizations').then(response => {
            for (let org of response.data) {
                commit('organizations/add', org, {root: true})
                if (rootState.organizations.selected === null) {
                    commit('organizations/setSelected', rootState.organizations.collection[0], {root: true})
                    dispatch('messenger/subscribe', null, {root: true})
                }
            }
        }).catch(error => {
            if (rootState.debug) console.log(error)
        })
    },

    async loadDivisions({commit, dispatch, rootState}, organizationId = null) {
        commit('changeLoadingState', true)
        let url = '/api/divisions'
        if (organizationId !== null) {
            url += '/' + organizationId
            commit('persons/clearCollection', null, {root: true})
            commit('divisions/clearCollection', null, {root: true})
        }
        window.axios.get(url).then(response => {
            for (let division of response.data) {
                for (let person of division.persons) {
                    person.divisions = [division.id]
                    dispatch('persons/add', person, {root: true})
                }
                commit('divisions/add', division, {root: true})
            }
            commit('changeLoadingState', false)
        }).catch(error => {
            if (rootState.debug) console.log(error)
            setTimeout(dispatch.loadDivisions, 2000) //TODO перезапуск при ошибке
        })
    },

    async loadPersons({commit, dispatch, rootState}, userId) {
        commit('changeLoadingState', true)
        window.axios.get('/users/persons').then(response => {
            for (let person of response.data) {
                for (let division of person.divisions) {
                    person.divisions = [division.id]
                    dispatch('persons/add', person, {root: true})
                }
            }
            commit('changeLoadingState', false)
        }).catch(error => {
            if (rootState.debug) console.log(error)
            setTimeout(dispatch.loadDivisions, 2000) //TODO перезапуск при ошибке
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
