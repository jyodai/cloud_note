<template>
  <div
    class="markdown-edit"
    @keydown.ctrl.s.prevent.stop="saveNote()"
  >
    <codemirror
      v-model:value="codemirrorContent"
      class="editor"
      :options="codemirrorOptions"
      @blur="onBlur"
      @changes="contentChange()"
    />
  </div>
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
      type    : Object,
      default : () => { return {}; },
    },
  },
  emits : [
    'saveNote',
    'blur',
  ],
  data () {
    return {
      contentChangeFlag : false,
      codemirrorContent : null,
      codemirrorOptions : {
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
    this.codemirrorContent = this.content.content;
    this.mergeCodemirrorOption();
  },
  mounted () {
    document.addEventListener('keydown', this.handleKeyDown);

    window.addEventListener('beforeunload', (e) => {
      if (this.contentChangeFlag) {
        // 空文字をセットすることでconfirmが出力される
        e.returnValue = '';
      }
    }, false);
  },
  beforeUnmount() {
    document.removeEventListener('keydown', this.handleKeyDown);
  },
  methods : {
    handleKeyDown(event) {
      if (this.isChangeTabEvent(event) && this.contentChangeFlag) {
        this.saveNote();
      }
    },
    mergeCodemirrorOption () {
      const user             = this.$store.getters['User/getUser'];
      const editorOption     = JSON.parse(user.note_setting.editor_option);
      this.codemirrorOptions = Object.assign(this.codemirrorOptions, editorOption);
    },
    reset () {
      this.codemirrorContent = this.content.content;
      this.contentChangeFlag = false;
    },
    contentChange () {
      if (this.content.content === this.codemirrorContent) {
        return;
      }
      this.contentChangeFlag = true;
    },
    onBlur () {
      this.$emit('blur', { id : this.content.id, content : this.codemirrorContent, });
      this.contentChangeFlag = false;
    },
    // ctrl + s で発火
    saveNote () {
      this.$emit('saveNote', { id : this.content.id, content : this.codemirrorContent, });
      this.contentChangeFlag = false;
    },
    isChangeTabEvent(event) {
      return event.ctrlKey && (event.key === 'j' || event.key === 'k');
    }
  },
};
</script>

<!-- eslint-disable-next-line vue-scoped-css/enforce-style-type -->
<style lang="scss">
.markdown-edit {
  height: 100%;
  width: 100%;
  .editor {
    font-family: 'Lucida Console', monospace;
    resize: none;
    height: 100%;
    width: 100%;
    padding:5px;
    font-size:14px;
    line-height: 18px;
    white-space : pre;
    font-weight: 500;
  }
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
