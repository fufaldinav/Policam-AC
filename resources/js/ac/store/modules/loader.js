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

    async loadDivisions({commit, dispatch, rootState}, withPersons) {
        commit('changeLoadingState', true)
        commit('divisions/clearCollection', null, {root: true})
        if (withPersons > 0) {
            commit('persons/clearCollection', null, {root: true})
        }
        let organizationId = rootState.organizations.selected.id
        window.axios.get(`/api/divisions/${organizationId}/${withPersons}`)
            .then(response => {
                for (let division of response.data) {
                    let persons = []
                    if (withPersons > 0) {
                        for (let person of division.persons) {
                            person.divisions = [division.id]
                            if (person.referral_code === null || person.referral_code.organization_id === organizationId || person.referral_code.activated === 1) {
                                dispatch('persons/add', person, {root: true})
                                if (person.referral_code !== null) {
                                    commit('rc/add', person.referral_code, {root: true})
                                }
                                persons.push(person.id)
                            }
                        }
                    }
                    division.persons = persons
                    commit('divisions/add', division, {root: true})
                }
                commit('changeLoadingState', false)
            })
            .catch(error => {
                if (rootState.debug) console.log(error)
                // setTimeout(dispatch('loadDivisions', withPersons), 2000) //TODO перезапуск при ошибке
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
                        } else {
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

    // async loadReferralCodes({commit, dispatch, rootState}, organizationId = 0) {
    //     commit('changeLoadingState', true)
    //     window.axios.get('/api/codes/' + organizationId)
    //         .then(response => {
    //             for (let rc of response.data) {
    //                 commit('rc/add', rc, {root: true})
    //             }
    //             commit('changeLoadingState', false)
    //         })
    //         .catch(error => {
    //             if (rootState.debug) console.log(error)
    //             setTimeout(dispatch('loadReferralCodes'), 2000) //TODO перезапуск при ошибке
    //         })
    // }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
