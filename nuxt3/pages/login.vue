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
definePageMeta({
  layout : null,
});
</script>

<script>
import { useUserStore } from '~/store/User';

export default {
  components : {
  },
  data () {
    return {
      userStore : useUserStore(),
      email     : '',
      password  : '',
    };
  },
  methods : {
    async execLogin () {
      const params = {
        'email'    : this.email,
        'password' : this.password,
      };
      await this.userStore.login(params);
      if (this.userStore.getUser) {
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
  background-color: $color-primary-dark;
  .login-form {
    position: relative;
    top : 100px;
    margin: auto;
    padding : 20px;
    width: 400px;
    background-color: $color-primary-light;
    border-radius: 10px;
  }
}
</style>
