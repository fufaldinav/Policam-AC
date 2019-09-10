import Vue from 'vue'

const state = {
    loading: true,
    step: 0,
    myRoles: [],
    roles: [
        {'type': 4, 'name': 'Родитель'},
        {'type': 9, 'name': 'Сотрудник'}
    ],
    currentStudent: {id: 0, f: null, i: null, o: null, gender: null, birthday: null, card: 0, division: 0},
    studentToUpdate: {id: 0, f: null, i: null, o: null, gender: null, birthday: null, card: 0, division: 0},
    students: [
        {f: 'Иванов', i: 'Пётр', o: 'Сергеевич', gender: 1, birthday: '2010-10-24', card: 2, division: 0},
        {f: 'Иванова', i: 'Лилия', o: 'Сергеевна', gender: 2, birthday: '2011-11-23', card: 3, division: 0}
    ],
    cards: [],
    studentFormType: 'add',
    organizations: [],
    divisions: [],
    user: {id: 0, f: null, i: null, o: null, gender: null, birthday: null, card: 0},
    userChecked: false
}

const getters = {
    getActiveCardsCount: state => {
        let cards = state.cards.filter(card => card.activated === 0)
        return cards.length
    },

    getDivisionById: state => divisionId => {
        let div = state.divisions.find(div => div.id === divisionId)
        if (div === undefined) return
        return div
    },

    getDivisionsByCard: state => cardId => {
        let card = state.cards.find(card => card.id === parseInt(cardId, 10))
        if (card === undefined) return []
        let org = state.organizations.find(org => org.id === card.organization_id)
        if (org === undefined) return []
        return state.divisions.filter(div => div.organization_id === org.id)
    },

    getOrganizationByCard: state => cardId => {
        let card = state.cards.find(card => card.id === parseInt(cardId, 10))
        if (card === undefined) return
        let org = state.organizations.find(org => org.id === card.organization_id)
        if (org === undefined) return
        return org
    }
}

const mutations = {
    addCode(state, code) {
        state.cards.push(code);
    },

    addOrganization(state, organization) {
        let index = state.organizations.indexOf(organization)
        if (index === -1) {
            state.organizations.push(organization)
        }
    },

    addDivision(state, division) {
        let div = state.divisions.find(div => (div.id === division.id))
        if (div === undefined) {
            state.divisions.push(division)
        }
    },

    toStep(state, step) {
        state.step = step
    },

    addRole(state, role) {
        state.myRoles.push(role)
    },

    removeRole(state, role) {
        let index = state.myRoles.indexOf(role)
        if (index > -1) {
            state.myRoles.splice(index, 1)
        }
    },

    addStudent(state, student) {
        state.students.push(student)
    },

    saveStudent(state, student) {
        let index = state.students.indexOf(state.currentStudent)
        if (index > -1) {
            state.students.splice(index, 1, JSON.parse(JSON.stringify(student)))
        }
    },

    removeStudent(state, student) {
        let index = state.students.indexOf(student)
        if (index > -1) {
            state.students.splice(index, 1)
        }
    },

    clearCurrentStudent(state, commit) {
        state.currentStudent = {f: null, i: null, o: null, gender: null, birthday: null, card: 0, division: 0}
        state.studentToUpdate = {f: null, i: null, o: null, gender: null, birthday: null, card: 0, division: 0}
    },

    revertStudent(state) {
        let index = state.students.indexOf(state.currentStudent)
        if (index > -1) {
            state.students.splice(index, 1, JSON.parse(JSON.stringify(state.studentToUpdate)))
        }
    },

    setCurrentStudent(state, student) {
        state.currentStudent = student
        state.studentToUpdate = JSON.parse(JSON.stringify(student))
    },

    setStudentFormType(state, type) {
        state.studentFormType = type
    },

    changeLoadingState(state, status) {
        state.loading = status
    },

    setUserCheckedStatus(state, status) {
        state.userChecked = status
    },

    setCardActivatedStatus(state, payload) {
        let card = state.cards.find(card => card.id === payload.cardId)
        if (card !== undefined) {
            card.activated = payload.status
        }
    }
}

const actions = {
    async loadCards({state, commit, dispatch, rootState}) {
        commit('changeLoadingState', true)
        window.axios.get('/api/codes')
            .then(response => {
                for (let code of response.data) {
                    commit('addCode', code)
                    dispatch('loadOrganization', code.organization_id)
                }
                commit('changeLoadingState', false)
            })
            .catch(error => {
                if (rootState.debug) console.log(error)
                setTimeout(dispatch('loadCards'), 2000) //TODO перезапуск при ошибке
            })
    },

    async loadDivisions({commit, dispatch, rootState}, organizationId) {
        commit('changeLoadingState', true)
        window.axios.get('/api/referral/divisions/' + organizationId)
            .then(response => {
                for (let division of response.data) {
                    commit('addDivision', division)
                }
                commit('changeLoadingState', false)
            })
            .catch(error => {
                if (rootState.debug) console.log(error)
                setTimeout(dispatch('loadDivisions'), 2000) //TODO перезапуск при ошибке
            })
    },

    async loadOrganization({commit, dispatch, rootState}, organizationId) {
        commit('changeLoadingState', true)
        window.axios.get('/api/referral/organization/' + organizationId)
            .then(response => {
                for (let organization of response.data) {
                    commit('addOrganization', organization)
                    dispatch('loadDivisions', organization.id)
                }
                commit('changeLoadingState', false)
            })
            .catch(error => {
                if (rootState.debug) console.log(error)
                setTimeout(dispatch('loadOrganization'), 2000) //TODO перезапуск при ошибке
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
