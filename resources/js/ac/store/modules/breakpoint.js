const state = {
    current: 'xs',
    breakpoints: {
        sm: 576,
        md: 768,
        lg: 992,
        xl: 1200
    },
    debounceTime: 100
}

const getters = {
    isXs: state => {
        return state.current === 'xs'
    },

    isSm: state => {
        return state.current === 'sm'
    },

    isMd: state => {
        return state.current === 'md'
    },

    isLg: state => {
        return state.current === 'lg'
    },

    isXl: state => {
        return state.current === 'xl'
    },
}

const mutations = {
    setCurrentBreakpoint(state, bp) {
        state.current = bp
    }
}

const actions = {
    async handleResize({state, commit}, width) {
        let current = state.current
        let newBreakpoint = state.current

        if (width < 576) {
            newBreakpoint = 'xs'
        } else if (width < 768) {
            newBreakpoint = 'sm'
        } else if (width < 992) {
            newBreakpoint = 'md'
        } else if (width < 1200) {
            newBreakpoint = 'lg'
        } else {
            newBreakpoint = 'xl'
        }

        if (newBreakpoint !== current) {
            commit('setCurrentBreakpoint', newBreakpoint)
        }
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
