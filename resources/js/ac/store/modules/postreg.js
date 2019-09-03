const state = {
    step: 2,
    myRoles: [4],
    roles: [
        {'type': 4, 'name': 'Родитель'},
        {'type': 9, 'name': 'Сотрудник'}
    ],
    currentStudent: {f: null, i: null, o: null, gender: null, birthday: null, card: 0},
    students: [
        {f: 'Иванов', i: 'Пётр', o: 'Сергеевич', gender: 0, birthday: '2010-10-24', card: 1},
        {f: 'Иванова', i: 'Лилия', o: 'Сергеевна', gender: 1, birthday: '2011-11-23', card: 2}
    ],
    cards: [
        {'id': 1, 'code': 5703010017826},
        {'id': 2, 'code': 5703010017826}
    ],
    studentFormType: 'add'
}

const getters = {}

const mutations = {
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

    removeStudent(state, student) {
        let index = state.students.indexOf(student)
        if (index > -1) {
            state.students.splice(index, 1)
        }
    },

    clearCurrentStudent(state) {
        state.currentStudent = {f: null, i: null, o: null, gender: null, birthday: null, card: ''}
    },

    setCurrentStudent(state, student) {
        state.currentStudent = student
    },

    setStudentFormType(state, type) {
        state.studentFormType = type
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
