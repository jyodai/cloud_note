<template>
  <div class="login">
    <div
      class="login-form"
    >
      <v-form>
        <v-text-field
          v-model="email"
          prepend-icon="mdi-account-circle"
          autocomplete="email"
          type="email"
          label="email"
        />
        <v-text-field
          v-model="password"
          prepend-icon="mdi-lock"
          sautocomplete="current-password"
          type="password"
          label="パスワード"
        />
        <div class="g-center">
          <v-btn
            @click="execLogin()"
          >
            ログイン
          </v-btn>
        </div>
      </v-form>
    </div>
  </div>
</template>

<script setup>
setPageLayout(false);
</script>

<script>

export default {
  components : {
  },
  data () {
    return {
      email    : '',
      password : '',
    };
  },
  methods : {
    async execLogin () {
      const params = {
        'email'    : this.email,
        'password' : this.password,
      };
      await this.$store.dispatch('User/login', params);
      if (this.$store.getters['User/getUser']) {
        this.$router.push('/');
      }
    },
  },
};

</script>

<style lang="scss" scoped>
.login {
  width: 100%;
  height: 100vh;
  background-color: #333333;
  .login-form {
    position: relative;
    top : 100px;
    margin: auto;
    padding : 20px;
    width: 400px;
    background-color: #222222;
    border-radius: 10px;
  }
}
</style>
