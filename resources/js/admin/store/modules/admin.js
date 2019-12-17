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
    controllers: []
}

const getters = {}

const mutations = {
    addController(state, controller) {
        let ctrl = state.controllers.find(ctrl => (ctrl.id === controller.id))
        if (ctrl === undefined) {
            state.controllers.push(controller)
        }
    }
}

const actions = {
    async loadControllers({commit, dispatch, rootState}) {
        return new Promise((resolve, reject) => {
            window.axios.get('/admin/controllers/')
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
