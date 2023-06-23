const state = () => ({
  user        : null,
  token       : null,
  isAdminUser : false,
})

const mutations = {
  setUser (state, user) {
    state.user = user
  },
  setToken (state, token) {
    state.token = token
  },
  setIsAdminUser (state, flag) {
    state.isAdminUser = flag
  },
}

const getters = {
  getUser     : state => state.user,
  getToken    : state => state.token,
  isAdminUser : state => state.isAdminUser,
}

const actions = {
  async login ({ commit, }, params) {
    const url = this.$config.public.apiUrl + '/users/token';
    await this.$axios.post(url, params)
      .then((response) => {
        commit('setUser', response.user)
        commit('setIsAdminUser', response.user.user_type === this.$const.USER_TYPE_ADMIN)
        commit('setToken', response.token)
        this.$util.sessionStorage.set('token', response.token)
      })
      .catch((e) => {
        alert(e.message)
      })
  },
  async logout ({ commit, }) {
    await this.$axios.delete(this.$config.public.apiUrl + '/users/token')
    this.$util.sessionStorage.remove('token')
    commit('setUser', null)
  },
  async setUser ({ commit, }) {
    const response = await this.$axios.get(this.$config.public.apiUrl + '/user')
    commit('setUser', response.user)
    commit('setToken', response.token)
    commit('setIsAdminUser', response.user.user_type === this.$const.USER_TYPE_ADMIN)
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  getters,
  actions,
}
