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
        commit('changeLoadingState', true)
        window.axios.get('/users/organizations')
            .then(response => {
                for (let org of response.data) {
                    commit('organizations/add', org, {root: true})
                    if (rootState.organizations.selected === null) {
                        commit('organizations/setSelected', rootState.organizations.collection[0], {root: true})
                        dispatch('messenger/subscribe', null, {root: true})
                    }
                }
                commit('changeLoadingState', false)
            })
            .catch(error => {
                if (rootState.debug) console.log(error)
                setTimeout(dispatch('loadOrganizations'), 2000) //TODO перезапуск при ошибке
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
        window.axios.get(url)
            .then(response => {
                for (let division of response.data) {
                    for (let person of division.persons) {
                        person.divisions = [division.id]
                        dispatch('persons/add', person, {root: true})
                    }
                    commit('divisions/add', division, {root: true})
                }
                commit('changeLoadingState', false)
            })
            .catch(error => {
                if (rootState.debug) console.log(error)
                setTimeout(dispatch('loadDivisions'), 2000) //TODO перезапуск при ошибке
            })
    },

    async loadPersons({commit, dispatch, rootState}) {
        commit('changeLoadingState', true)
        window.axios.get('/users/persons')
            .then(response => {
                for (let person of response.data) {
                    let divisions = []
                    person.organizations = {basic: null, additional: []}
                    for (let division of person.divisions) {
                        divisions.push(division.id)
                        if (division.organization.type === 1) {
                            person.organizations.basic = division.organization.id
                        } else if (division.organization.type === 2) {
                            person.organizations.additional.push(division.organization.id)
                        }
                    }
                    person.divisions = divisions
                    dispatch('persons/add', person, {root: true})
                }
                commit('changeLoadingState', false)
            })
            .catch(error => {
                if (rootState.debug) console.log(error)
                setTimeout(dispatch('loadPersons'), 2000) //TODO перезапуск при ошибке
            })
    },

    async loadReferralCodes({commit, dispatch, rootState}) {
        commit('changeLoadingState', true)
        window.axios.get('/users/referral_codes')
            .then(response => {
                for (let card of response.data) {
                    let code = parseInt(card.code)
                    let wiegand = ('000000000000' + code.toString(16).toUpperCase()).slice(-12)
                    commit('cards/addReferralCard', {wiegand: wiegand}, {root: true})
                }
                commit('changeLoadingState', false)
            })
            .catch(error => {
                if (rootState.debug) console.log(error)
                setTimeout(dispatch('loadReferralCodes'), 2000) //TODO перезапуск при ошибке
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
