<template>
  <div />
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
    this.execLogout()
    this.$router.push('/login')
  },
  methods: {
    async execLogout () {
      const token = sessionStorage.getItem('token')
      const params = new URLSearchParams()
      params.append('token', token)
      await this.$axios.delete(this.$config.public.apiUrl + '/users/token', { data: params, }, this.config)

      if (('sessionStorage' in window) && (window.sessionStorage !== null)) {
        sessionStorage.removeItem('token')
      }
    },
  },
}

</script>
