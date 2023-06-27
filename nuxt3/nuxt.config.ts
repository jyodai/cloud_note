// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  ssr: false,
  runtimeConfig: {
    public: {
      severAlias: process.env.SERVER_ALIAS,
      apiUrl: process.env.API_SERVER_URl,
    },
  },
  css: [
    'vuetify/lib/styles/main.sass',
    '@mdi/font/css/materialdesignicons.css',
    '~/assets/common.scss',
    '~/assets/table.scss',
    '~/assets/markdown.scss',
    '~/assets/markdown-print.scss',
    'vue-final-modal/style.css',
  ],
  build: {
    transpile: ['vuetify'],
  },
  app: {
    baseURL: process.env.SERVER_ALIAS,
    head : {
      titleTemplate : 'CloudNote %s',
      meta          : [
        { charset: 'utf-8', },
        { name: 'viewport', content: 'width=device-width, initial-scale=1', },
      ],
    },
  },
})
