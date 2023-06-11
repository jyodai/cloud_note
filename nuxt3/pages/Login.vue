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

<script>

export default {
  components: {
  },
  layout: 'simple',
  data () {
    return {
      email    : '',
      password : '',
      config   : {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
      },
    }
  },
  mounted () {
    this.sessionCheck()
  },
  methods: {
    async execLogin () {
      const params = new URLSearchParams()
      params.append('email', this.email)
      params.append('password', this.password)
      const response = await this.$axios.$post(process.env.API_SERVER_URl + '/users/token', params, this.config)
        .catch((e) => {
          alert(e.message)
          return false
        })
      if (response.token) {
        this.$store.commit('User/setUser', response.user)
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
      const queryStr = '?token=' + value
      const response = await this.$axios.$get(process.env.API_SERVER_URl + '/user' + queryStr)

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
