export default {
    setUserRole(state, role) {
        state.userRole = role
    },

    setPersonsMustBeLoaded(state) {
        state.personsMustBeLoaded = 1
    },

    setPersonsShouldNotBeLoaded(state) {
        state.personsMustBeLoaded = 0
    }
}
