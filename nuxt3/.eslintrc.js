module.exports = {
  root          : true,
  parserOptions : {
    // JavaScriptの非標準の構文もlintできるようにするパッケージ
    parser            : '@babel/eslint-parser',
    requireConfigFile : false,
  },
  extends: [
    'plugin:vue/vue3-recommended',
    '@vue/typescript/recommended',
    'eslint:recommended',
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
