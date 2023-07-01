<template>
  <codemirror
    v-model:value="codemirrorContent"
    class="editor"
    :options="getOption()"
    @changes="changes"
  />
</template>

<script>
import codemirror from 'codemirror-editor-vue3';
import 'codemirror/lib/codemirror.css';
import 'codemirror/theme/base16-dark.css';
import 'codemirror/keymap/vim.js';

export default {
  components : {
    codemirror,
  },
  props : {
    content : {
      type    : String,
      default : "",
    },
    options : {
      type    : Object,
      default : () => { return {}; },
    },
  },
  emits : [
    'changes',
  ],
  data () {
    return {
      codemirrorContent : null,
      defaultOptions    : {
        tabSize         : 4,
        indentUnit      : 4,
        smartIndent     : true,
        styleActiveLine : true,
        lineNumbers     : true,
        line            : true,
        mode            : 'text',
        lineWrapping    : false,
        theme           : 'base16-dark',
        extraKeys       : {
          Tab : (cm) => {
            if (cm.somethingSelected()) {
              cm.execCommand('indentMore');
              return;
            }
            cm.execCommand('insertSoftTab');
          },
          'Shift-Tab' : cm => cm.execCommand('indentLess'),
        },
      },
    };
  },
  watch : {
    content () {
      this.reset();
    },
  },
  created () {
    this.codemirrorContent = this.content;
  },
  methods : {
    reset () {
      this.codemirrorContent = this.content;
    },
    getOption () {
      return Object.assign(this.defaultOptions, this.options);
    },
    changes () {
      this.$emit('changes', this.codemirrorContent);
    },
  },
};
</script>

<!-- eslint-disable-next-line vue-scoped-css/enforce-style-type -->
<style lang="scss">
.editor {
  font-family: 'Lucida Console', monospace;
  resize: none;
  height: 100%;
  width: 100%;
  font-size:14px;
  line-height: 18px;
  white-space : pre;
  font-weight: 500;
  .CodeMirror {
    height: 100%;
    width: 100%;
    .CodeMirror-dialog-bottom {
      position: absolute;
      bottom: 5px;
      left : 5px;
      width :95%;
    }
  }
}
</style>
