import { createStore } from 'vuex'

export const store = createStore({
  state: function () {
    return {
      authenticated: localStorage.getItem('authenticated') !== 'false',
      role: localStorage.getItem('role'),
      user_id: localStorage.getItem('user_id'),
    }
  },
  mutations: {
    setAuthenticated(state, authenticated) {
      // mutate state
      state.authenticated = !!authenticated;
      localStorage.setItem('authenticated', authenticated);
    },
    setRole(state, role) {
      state.role = role;
      localStorage.setItem('role', role);
    },
    setUserId(state, user_id) {
      state.user_id = user_id;
      localStorage.setItem('user_id', user_id)
    }
  },
  getters: {
    isAuthenticated(state) {
      return state.authenticated;
    },
    getRole(state) {
      return state.role;
    },
    getUserId(state) {
      return state.user_id;
    }
  }
})

export default store