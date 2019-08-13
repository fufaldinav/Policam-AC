import {Organization} from '../../classes'

const state = {
    collection: [],
    basic: [],
    additional: [],
    selected: null
}

const getters = {
    getById: state => id => {
        return state.collection.find((value) => {
            if (value.id === id) return true
        })
    },

    sorted: state => {
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

        return state.collection.sort((a, b) => {
            if (a.type < b.type) return -1
            if (a.type > b.type) return 1
            let sortByName = naturalCompare(a.name, b.name)
            if (sortByName !== 0) return sortByName
            return 0
        })
    }
}

const mutations = {
    add(state, org) {
        state.collection.push(new Organization(org))
    },

    setSelected(state, org) {
        state.selected = org
        window.Ac.$bus.$emit('OrgSelected', org.id)
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
