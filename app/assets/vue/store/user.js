import UserAPI from '../api/users';

export default {
    namespaced: true,
    state: {
        isLoading: false,
        error: null,
        users: [],
    },
    getters: {
        isLoading (state) {
            return state.isLoading;
        },
        hasError (state) {
            return state.error !== null;
        },
        error (state) {
            return state.error;
        },
        hasUsers (state) {
            return state.users.length > 0;
        },
        users (state) {
            return state.users;
        },
    },
    mutations: {
        ['CREATING_USER'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['CREATING_USER_SUCCESS'](state, user) {
            state.isLoading = false;
            state.error = null;
            state.users.unshift(user);
        },
        ['CREATING_USER_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.users = [];
        },
        ['FETCHING_USERS'](state) {
            state.isLoading = true;
            state.error = null;
            state.users = [];
        },
        ['FETCHING_USERS_SUCCESS'](state, users) {
            state.isLoading = false;
            state.error = null;
            state.users = users;
        },
        ['FETCHING_USERS_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.users = [];
        },
    },
    actions: {
        createUser ({commit}, message) {
            commit('CREATING_USER');
            return UserAPI.create(message)
                .then(res => commit('CREATING_USER_SUCCESS', res.data))
                .catch(err => commit('CREATING_USER_ERROR', err));
        },
        fetchUsers ({commit}) {
            commit('FETCHING_USERS');
            return UserAPI.getAll()
                .then(res => commit('FETCHING_USERS_SUCCESS', res.data))
                .catch(err => commit('FETCHING_USERS_ERROR', err));
        },
    },
}
