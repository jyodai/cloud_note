<template>
  <v-card width="400px" class="mx-auto mt-5">
    <v-form class="mx-auto pa-3">
      <v-text-field v-model="email" prepend-icon="mdi-account-circle" autocomplete="email" type="email" label="email" />
      <v-text-field v-model="password" prepend-icon="mdi-lock" sautocomplete="current-password" type="password" label="パスワード" />
      <v-card-actions>
        <v-btn class="info" @click="execLogin()">
          ログイン
        </v-btn>
      </v-card-actions>
    </v-form>
  </v-card>
</template>

<script setup>
setPageLayout(false);
</script>

<script>

export default {
  components: {
  },
  data () {
    return {
      email    : '',
      password : '',
    }
  },
  mounted () {
    this.sessionCheck()
  },
  methods: {
    async execLogin () {
      const url = this.$config.public.apiUrl + '/users/token';
      const params = new URLSearchParams()
      params.append('email', this.email)
      params.append('password', this.password)
      const response = await this.$axios.post(url, params)
        .catch((e) => {
          alert(e.message)
          return false
        })
      if (response.token) {
        this.$store.commit('User/setUser', response.user)
        this.$store.commit('User/setIsAdminUser', response.user.user_type === this.$const.USER_TYPE_ADMIN)
        this.$store.commit('User/setToken', response.token)
        this.$emit('set')
        this.sessionSave(response.token)
        this.$router.push('/')
      }

      if (response.message) {
        alert(response.message)
      }
    },
    sessionSave (token) {
      if (('sessionStorage' in window) && (window.sessionStorage !== null)) {
        sessionStorage.setItem('token', token)
      }
    },
    async getUser (value) {
      const url = this.$config.public.apiUrl + '/user';
      const response = await this.$axios.get(url)

      if (response.user) {
        this.$store.commit('User/setUser', response.user)
        this.$store.commit('User/setToken', value)
        this.$emit('set')
      }
    },
    sessionCheck () {
      if (('sessionStorage' in window) && (window.sessionStorage !== null)) {
        // ストレージ使用可能
        const token = sessionStorage.getItem('token')
        if (token) {
          this.getUser(token)
        }
      }
    },
  },
}

</script>
