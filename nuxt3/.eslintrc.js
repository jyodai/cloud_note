// eslint-disable-next-line no-undef
module.exports = {
  root          : true,
  parserOptions : {
    'ecmaVersion' : 2020,
    'parser'      : '@typescript-eslint/parser',
  },
  extends : [
    'plugin:vue/vue3-recommended',
    '@vue/typescript/recommended',
    'eslint:recommended',
    'plugin:vue-scoped-css/vue3-recommended',
  ],
  globals : {
    Prism                     : false,
    PropType                  : false,
    defineNuxtConfig          : false,
    defineNuxtPlugin          : false,
    defineNuxtRouteMiddleware : false,
    definePageMeta            : false,
    mermaid                   : true,
    navigateTo                : false,
    process                   : false,
    setPageLayout             : false,
    useNuxtApp                : false,
    useRuntimeConfig          : false,
  },
  plugins : [
    'align-assignments',
  ],
  rules : {
    indent         : ['error', 2],
    eqeqeq         : 'error', // 厳密な比較のみ許可
    camelcase      : 'off',
    'prefer-const' : 2, //再代入がなければConst
    // 垂直方向のコロンを揃える
    'key-spacing'  : [
      2,
      {
        'beforeColon' : true,
        'afterColon'  : true,
        'align'       : 'colon',
      },
    ],
    'no-var'      : 2, //varを禁止
    'no-debugger' : 'warn',

    "semi"                    : "off",
    "@typescript-eslint/semi" : ["error", "always"],

    "no-unused-vars"                    : "off",
    "@typescript-eslint/no-unused-vars" : ["error"],

    // 以降プラグインから追加

    'vue/multi-word-component-names' : [
      'error',
      {
        'ignores' : [
          'index',
          'login',
          'default',
          'dashboard',
          'error',
        ],
      }
    ],

    // 垂直方向のイコールを揃える
    'align-assignments/align-assignments' : [2, { 'requiresOnly' : false } ]
  },
};
