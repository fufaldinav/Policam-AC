const state = {
    loading: true
}

const getters = {

}

const mutations = {
    changeLoadingState(state, loading) {
        state.loading = loading;
    }
}

const actions = {
    async loadData({commit, dispatch}) {
        window.axios.get('/api/divisions').then(function (response) {
            for (let division of response.data) {
                for (let person of division.persons) {
                    person.divisions = [division.id];
                    dispatch('persons/add', person, {root: true});
                }
                commit('divisions/add', division, {root: true});
            }
            commit('changeLoadingState', false);
        }).catch(function (error) {
            console.log(error);
            setTimeout(dispatch.loadData, 2000); //TODO перезапуск при ошибке
        });
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
