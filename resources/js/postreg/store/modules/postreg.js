import Vue from 'vue'

const state = {
    loading: true,
    step: 2,
    myRoles: [4, 9],
    roles: [
        {'type': 4, 'name': 'Родитель'},
        {'type': 9, 'name': 'Сотрудник'}
    ],
    currentStudent: {id: 0, f: null, i: null, o: null, gender: null, birthday: null, code: 0, division: 0},
    studentToUpdate: {id: 0, f: null, i: null, o: null, gender: null, birthday: null, code: 0, division: 0},
    students: [
        {f: 'Иванов', i: 'Пётр', o: 'Сергеевич', gender: 1, birthday: '2010-10-24', code: 0, division: 0},
        {f: 'Иванова', i: 'Лилия', o: 'Сергеевна', gender: 2, birthday: '2011-11-23', code: 0, division: 0}
    ],
    codes: [],
    studentFormType: 'add',
    organizations: [],
    divisions: [],
    user: {id: 0, f: null, i: null, o: null, gender: null, birthday: null, code: 0},
    userChecked: false
}

const getters = {
    getActiveCodesCount: state => {
        let codes = state.codes.filter(code => code.activated === 0)
        return codes.length
    },

    getDivisionById: state => divisionId => {
        let div = state.divisions.find(div => div.id === divisionId)
        if (div === undefined) return
        return div
    },

    getDivisionsByCode: state => codeId => {
        let code = state.codes.find(code => code.id === parseInt(codeId, 10))
        if (code === undefined) return []
        let org = state.organizations.find(org => org.id === code.organization_id)
        if (org === undefined) return []
        return state.divisions.filter(div => div.organization_id === org.id)
    },

    getOrganizationByCode: state => codeId => {
        let code = state.codes.find(code => code.id === parseInt(codeId, 10))
        if (code === undefined) return
        let org = state.organizations.find(org => org.id === code.organization_id)
        if (org === undefined) return
        return org
    }
}

const mutations = {
    addCode(state, code) {
        let ref = state.codes.find(ref => (ref.id === code.id))
        if (ref === undefined) {
            state.codes.push(code)
        }
    },

    addOrganization(state, organization) {
        let org = state.organizations.find(org => (org.id === organization.id))
        if (org === undefined) {
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
        state.currentStudent = {f: null, i: null, o: null, gender: null, birthday: null, code: 0, division: 0}
        state.studentToUpdate = {f: null, i: null, o: null, gender: null, birthday: null, code: 0, division: 0}
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

    setCodeActivatedStatus(state, payload) {
        let code = state.codes.find(code => code.id === payload.codeId)
        if (code !== undefined) {
            code.activated = payload.status
        }
    }
}

const actions = {
    async loadCodes({state, commit, dispatch, rootState}) {
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
                setTimeout(dispatch('loadCodes'), 2000) //TODO перезапуск при ошибке
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
    },

    async getReferral({rootState}, code) {
        return new Promise((resolve, reject) => {
            window.axios.get('/api/referral/checkcode/' + code)
                .then(response => {
                    resolve(response.data)
                })
                .catch(error => {
                    reject(error)
                })
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
