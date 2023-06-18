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
  async logout ({ commit, }) {
    const config = {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
    };
    const token = sessionStorage.getItem('token')
    const params = new URLSearchParams()
    params.append('token', token)
    await this.$axios.delete(this.$config.public.apiUrl + '/users/token', { data: params, }, config)

    if (('sessionStorage' in window) && (window.sessionStorage !== null)) {
      sessionStorage.removeItem('token')
    }

    commit('setUser', null)
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  getters,
  actions,
}
