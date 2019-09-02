const state = {
    step: 0,
    role: 0,
    roles: [
        {'type': 4, 'name': 'Родитель'},
        {'type': 9, 'name': 'Сотрудник'},
        {'type': 3, 'name': 'Секретарь'},
        {'type': 7, 'name': 'Руководитель'},
    ],
    students: [],
    cards: [
        {'id': 1, 'code': 5703010017826}
    ]
}

const getters = {}

const mutations = {
    toStep(state, step) {
        state.step = step
    },

    setRole(state, role) {
        state.role = role
    },

    addStudent(state, student) {
        state.students.push(student)
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
