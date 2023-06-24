module.exports = {
  root          : true,
  parserOptions : {
    'ecmaVersion' : 2020,
    'parser'      : '@typescript-eslint/parser',
  },
  extends: [
    'plugin:vue/vue3-recommended',
    '@vue/typescript/recommended',
    'eslint:recommended',
    'plugin:vue-scoped-css/vue3-recommended',
  ],
  globals: {
    mermaid : true,
    Prism   : false,
  },
  rules: {
    camelcase      : 'off',
    'comma-dangle' : [
      'error',
      {
        arrays    : 'always',
        objects   : 'always',
        imports   : 'never',
        exports   : 'never',
        functions : 'never',
      },
    ],
    'prefer-const' : 2,
    'key-spacing'  : [
      'error',
      {
        align: {
          beforeColon : true,
          afterColon  : true,
          on          : 'colon',
        },
      },
    ],
    'no-var': 2,
  },
}
