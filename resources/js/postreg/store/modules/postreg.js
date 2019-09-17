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

const state = {
    loading: true,
    step: 0,
    myRoles: [],
    roles: [
        {'type': 4, 'name': 'Родитель'},
        {'type': 9, 'name': 'Сотрудник'}
    ],
    currentStudent: {
        id: 0,
        f: null,
        i: null,
        o: null,
        gender: null,
        birthday: null,
        code: 0,
        organization: 0,
        additionalOrganizations: [],
        division: 0
    },
    studentToUpdate: {
        id: 0,
        f: null,
        i: null,
        o: null,
        gender: null,
        birthday: null,
        code: 0,
        organization: 0,
        additionalOrganizations: [],
        division: 0
    },
    students: [],
    codes: [],
    studentFormType: 'add',
    organizations: [],
    divisions: [],
    user: {id: null, f: null, i: null, o: null, gender: null, birthday: null, code: 0, organization: 0},
    userChecked: false
}

const getters = {
    getCodeById: state => codeId => {
        return state.codes.find(code => code.id === codeId)
    },

    getActiveCodesCount: state => {
        let codes = state.codes.filter(code => code.activated === 0)
        return codes.length
    },

    checkCodeActivity: state => code => {
        let ref = state.codes.find(ref => ref.code === code)
        if (ref === undefined) return false
        return ref.activated
    },

    getDivisionById: state => divisionId => {
        return state.divisions.find(div => div.id === divisionId)
    },

    getDivisionsByOrg: state => orgId => {
        return state.divisions.filter(div => div.organization_id === orgId)
    },

    getOrganizationById: state => organizationId => {
        return state.organizations.find(org => org.id === organizationId)
    },

    getOrganizationByCode: state => codeId => {
        let code = state.codes.find(code => code.id === parseInt(codeId, 10))
        if (code === undefined) return
        let org = state.organizations.find(org => org.id === code.organization_id)
        if (org === undefined) return
        return org
    },

    studentsCount: state => {
        return state.students.length
    },

    getSortedDivisionsByOrg: state => orgId => {
        state.divisions.sort((a, b) => {
            if (a.type < b.type) return -1
            if (a.type > b.type) return 1
            let sortByName = naturalCompare(a.name, b.name)
            if (sortByName !== 0) return sortByName
            return 0
        })

        return state.divisions.filter(div => div.organization_id === orgId)
    },

    getSortedOrganizations: state => {
        return state.organizations.sort((a, b) => {
            if (a.type < b.type) return -1
            if (a.type > b.type) return 1
            let sortByName = naturalCompare(a.name, b.name)
            if (sortByName !== 0) return sortByName
            return 0
        })
    },

    getSortedStudents: state => {
        return state.students.sort((a, b) => {
            if (a.f < b.f) return -1
            if (a.f > b.f) return 1
            if (a.i < b.i) return -1
            if (a.i > b.i) return 1
            if (a.o < b.o) return -1
            if (a.o > b.o) return 1
            return 0
        })
    }
}

const mutations = {
    setUserInfo(state, user) {
        state.user.id = user.id
        state.user.f = user.last_name
        state.user.i = user.name
    },

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

    addAdditionalOrganization(state, ao) {
        let org = state.organizations.find(org => (org.id === ao.id))
        if (org === undefined) {
            state.additionalOrganizations.push(ao)
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

    setCurrentStudent(state, student) {
        state.currentStudent = student
        state.studentToUpdate = JSON.parse(JSON.stringify(student))
    },

    clearCurrentStudent(state) {
        state.currentStudent = {
            id: 0,
            f: null,
            i: null,
            o: null,
            gender: null,
            birthday: null,
            code: 0,
            organization: 0,
            additionalOrganizations: [],
            division: 0
        }
        state.studentToUpdate = {
            id: 0,
            f: null,
            i: null,
            o: null,
            gender: null,
            birthday: null,
            code: 0,
            organization: 0,
            additionalOrganizations: [],
            division: 0
        }
    },

    revertStudent(state) {
        let index = state.students.indexOf(state.currentStudent)
        if (index > -1) {
            state.students.splice(index, 1, JSON.parse(JSON.stringify(state.studentToUpdate)))
        }
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
            code.activated = payload.activated
        }
    }
}

const actions = {
    async loadUserInfo({commit, dispatch, rootState}) {
        commit('changeLoadingState', true)
        window.axios.get('/api/user')
            .then(response => {
                commit('setUserInfo', response.data)
                dispatch('loadCodes')
                    .then(response => {
                        for (let code of response) {
                            if (code.organization_id === 0) continue
                            commit('addCode', code)
                            dispatch('loadOrganization', {organizationId: code.organization_id})
                                .then(response => {
                                    for (let organization of response) {
                                        commit('addOrganization', organization)
                                        dispatch('loadDivisions', organization.id)
                                            .then(response => {
                                                for (let division of response) {
                                                    commit('addDivision', division)
                                                }
                                                commit('changeLoadingState', false)
                                            })
                                            .catch(error => {
                                                if (rootState.debug) console.log(error)
                                            })
                                    }
                                    if (response.length === 0) {
                                        commit('changeLoadingState', false)
                                    }
                                })
                                .catch(error => {
                                    if (rootState.debug) console.log(error)
                                })
                        }
                        if (response.length === 0) {
                            commit('changeLoadingState', false)
                        }
                    })
                    .catch(error => {
                        if (rootState.debug) console.log(error)
                    })
            })
            .catch(error => {
                if (rootState.debug) console.log(error)
                setTimeout(dispatch('loadUserInfo'), 2000) //TODO перезапуск при ошибке
            })
    },

    async loadCodes({commit, dispatch, rootState}) {
        return new Promise((resolve, reject) => {
            window.axios.get('/api/codes/')
                .then(response => {
                    resolve(response.data)
                })
                .catch(error => {
                    reject(error)
                })
        })
    },

    async loadOrganization({commit, dispatch, rootState}, payload) {
        if (payload.type === undefined) {
            payload.type = 0
        }
        return new Promise((resolve, reject) => {
            window.axios.get('/api/referral/organizations/' + payload.type + '/' + payload.organizationId)
                .then(response => {
                    resolve(response.data)
                })
                .catch(error => {
                    reject(error)
                })
        })
    },

    async loadOrganizations({commit, dispatch, rootState}, type = 1) {
        return new Promise((resolve, reject) => {
            window.axios.get('/api/referral/organizations/' + type)
                .then(response => {
                    resolve(response.data)
                })
                .catch(error => {
                    reject(error)
                })
        })
    },

    async loadDivisions({commit, dispatch, rootState}, organizationId) {
        return new Promise((resolve, reject) => {
            window.axios.get('/api/referral/divisions/' + organizationId)
                .then(response => {
                    resolve(response.data)
                })
                .catch(error => {
                    reject(error)
                })
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
    },

    async sendDataToServer({state}) {
        return new Promise((resolve, reject) => {
            window.axios.post('/api/referral/data', {
                myRoles: state.myRoles,
                students: state.students,
                user: state.user
            })
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
