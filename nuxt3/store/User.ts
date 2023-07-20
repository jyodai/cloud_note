import { defineStore } from 'pinia';
import User from '~/types/models/user';

const nuxtApp = useNuxtApp();

interface State {
  user : User | null,
  token : string | null,
  isAdminUser :  boolean,
}

interface LoginParams {
  'email': string,
  'password' : string,
}

export const useUserStore = defineStore({
  id    : 'user',
  state : (): State => ({
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
    async login (params: LoginParams): Promise<void> {
      const url = nuxtApp.$config.public.apiUrl + '/users/token';
      await nuxtApp.$axios.post(url, params)
        .then((response) => {
          this.user        = response.data;
          this.isAdminUser = response.data.user_type === nuxtApp.$const.USER_TYPE_ADMIN;
          this.token       = response.data.api_token;
          nuxtApp.$util.sessionStorage.set('token', response.data.api_token);
        })
        .catch((e) => {
          alert(e.message);
        });
    },
    async logout (): Promise<void> {
      await nuxtApp.$axios.delete(nuxtApp.$config.public.apiUrl + '/users/token');
      nuxtApp.$util.sessionStorage.remove('token');
      this.user = null;
    },
    async setUser (): Promise<void> {
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
