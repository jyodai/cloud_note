import { defineStore } from 'pinia';

const nuxtApp = useNuxtApp();

export const useUserStore = defineStore({
  id    : 'user',
  state : () => ({
    user        : null,
    token       : null,
    isAdminUser : false,
  }),
  getters : {
    getUser        : state => state.user,
    getToken       : state => state.token,
    getIsAdminUser : state => state.isAdminUser,
  },
  actions : {
    async login (params) {
      const url = nuxtApp.$config.public.apiUrl + '/users/token';
      await nuxtApp.$axios.post(url, params)
        .then((response) => {
          this.user        = response.user;
          this.isAdminUser = response.user.user_type === nuxtApp.$const.USER_TYPE_ADMIN;
          this.token       = response.token;
          nuxtApp.$util.sessionStorage.set('token', response.token);
        })
        .catch((e) => {
          alert(e.message);
        });
    },
    async logout () {
      await nuxtApp.$axios.delete(nuxtApp.$config.public.apiUrl + '/users/token');
      nuxtApp.$util.sessionStorage.remove('token');
      this.user = null;
    },
    async setUser () {
      await nuxtApp.$axios.get(nuxtApp.$config.public.apiUrl + '/users/me')
        .then((response) => {
          this.user        = response.data;
          this.token       = nuxtApp.$util.sessionStorage.get('token');
          this.isAdminUser = response.data.user_type === nuxtApp.$const.USER_TYPE_ADMIN;
        })
      ;
    },
  }
});
