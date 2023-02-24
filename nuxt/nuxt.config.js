import path from 'path'
import fs from 'fs'
import colors from 'vuetify/es5/util/colors'

require('dotenv').config()
const { API_SERVER_URl, SERVER_ALIAS, SSL_PATH, } = process.env

let server = null
if (process.env.NODE_ENV === 'development') {
  server = {
    port  : 8887,
    host  : '0.0.0.0',
    https : {
      key  : fs.readFileSync(path.resolve(process.env.SSL_PATH, 'server.key')),
      cert : fs.readFileSync(path.resolve(process.env.SSL_PATH, 'server.crt')),
    },
  }
}

export default {
  mode : 'spa',
  /*
  ** Headers of the page
  */
  head : {
    titleTemplate : 'CloudNote %s',
    // title: process.env.npm_package_name || '',
    meta          : [
      { charset: 'utf-8', },
      { name: 'viewport', content: 'width=device-width, initial-scale=1', },
      { hid: 'description', name: 'description', content: process.env.npm_package_description || '', },
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: process.env.SERVER_ALIAS + '/favicon.ico', },
    ],
    script: [],
    base: {
      href: 'router.base',
    },
  },
  /*
  ** Customize the progress-bar color
  */
  loading : { color: '#fff', },
  /*
  ** Global CSS
  */
  css     : [
    { src: '~/assets/common.scss', },
  ],
  /*
  ** Plugins to load before mounting the App
  */
  plugins: [
    '@/plugins/load-common',
    '@/plugins/router-option',
    '@/plugins/prism',
    '@/plugins/VueFinalModal',
  ],
  /*
  ** Nuxt.js dev-modules
  */
  buildModules: [
    '@nuxtjs/vuetify',
    // ホットリロード時にESLintを実行
    ['@nuxtjs/eslint-module', {fix: true}],
  ],
  /*
  ** Nuxt.js modules
  */
  modules: [
    '@nuxtjs/axios',
    '@nuxtjs/dotenv',
    '@nuxtjs/markdownit',
  ],
  /*
  ** vuetify module configuration
  ** https://github.com/nuxt-community/vuetify-module
  */
  vuetify: {
    customVariables: [
      '~/assets/variables.scss',
    ],
    theme: {
      dark   : true,
      themes : {
        dark: {
          primary   : colors.blue.darken2,
          accent    : colors.grey.darken3,
          secondary : colors.amber.darken3,
          info      : colors.teal.lighten1,
          warning   : colors.amber.base,
          error     : colors.deepOrange.accent4,
          success   : colors.green.accent3,
        },
      },
    },
  },
  /*
  ** Build configuration
  */
  build: {
    /*
    ** You can extend webpack config here
    */
    cache: true,
    extend (config, ctx) {
    },
    transpile: [
      'vue-final-modal',
    ],
  },
  server : server,
  router : {
    base: process.env.SERVER_ALIAS,
  },
  env: {
    SERVER_ALIAS,
    API_SERVER_URl,
    SSL_PATH,
  },
  markdownit: {
    injected   : true, // $mdを利用してmarkdownをhtmlにレンダリングする
    breaks     : false, // 改行コードを<br>に変換する
    linkify    : false, // URLに似たテキストをリンクに自動変換する
    typography : false, // 言語に依存しないきれいな 置換 + 引用符 を有効にします。
    html       : true, // HTML タグを有効にする
    use: [
      "markdown-it-plugin-mermaid",
    ],
  },
}
