<template>
  <v-app dark>
    <v-app-bar
      app
    >
      <v-toolbar-title v-text="title" />
      <v-spacer />
    </v-app-bar>
    <v-main>
      <slot />
    </v-main>
    <v-footer
      app
    >
      <span>&copy; {{ new Date().getFullYear() }}</span>
    </v-footer>
  </v-app>
</template>
<script>

export default {
  components: {
  },
  data () {
    return {
      title: 'Memo',
    }
  },
  mounted () {
  },
}
</script>
