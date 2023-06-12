const state = () => ({
  user  : null,
  token : null,
})

const mutations = {
  setUser (state, user) {
    state.user = user
  },
  setToken (state, token) {
    state.token = token
  },
}

const getters = {
  getUser  : state => state.user,
  getToken : state => state.token,
}

const actions = {
}

export default {
  namespaced: true,
  state,
  mutations,
  getters,
  actions,
}
