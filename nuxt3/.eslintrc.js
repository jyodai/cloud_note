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
    mermaid : true,
    Prism   : false,
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

    // 以降プラグインから追加

    // 垂直方向のイコールを揃える
    "align-assignments/align-assignments" : [2, { "requiresOnly" : false } ]
  },
}
