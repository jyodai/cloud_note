<template>
  <div
    class="markdown-edit"
    @keydown.ctrl.83.prevent.stop="saveNote()"
  >
    <codemirror
      v-model="content"
      class="editor"
      :options="codemirrorOptions"
      @blur="onBlur"
      @changes="contentChange()"
    />
  </div>
</template>

<script>
import { codemirror } from 'vue-codemirror'
import 'codemirror/lib/codemirror.css'
import 'codemirror/theme/base16-dark.css'

export default {
  components: {
    codemirror,
  },
  props: {
    note: {
      type    : Object,
      default : () => {},
    },
  },
  emit: [
    'saveNote',
    'blur',
  ],
  data () {
    return {
      contentChangeFlag : false,
      content           : null,
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
          Tab: (cm) => {
            if (cm.somethingSelected()) {
              cm.execCommand('indentMore')
              return
            }
            cm.execCommand('insertSoftTab')
          },
          'Shift-Tab': cm => cm.execCommand('indentLess'),
        },
      },
    }
  },
  computed: {
    changeNote () {
      return this.note
    },
  },
  watch: {
    changeNote (newVal, oldVal) {
      this.content = this.note.content
    },
  },
  created () {
    this.content = this.note.content
  },
  mounted () {
    const self = this
    window.addEventListener('beforeunload', function (e) {
      if (self.contentChangeFlag) {
        // 空文字をセットすることでconfirmが出力される
        e.returnValue = ''
      }
    }, false)
  },
  methods: {
    contentChange () {
      this.contentChangeFlag = true
    },
    onBlur () {
      this.$emit('blur', { id: this.note.id, content: this.content, })
      this.contentChangeFlag = false
    },
    // ctrl + s で発火
    saveNote () {
      this.$emit('saveNote', { id: this.note.id, content: this.content, })
      this.contentChangeFlag = false
    },
  },
}
</script>

<style lang="scss" scoped>
.markdown-edit::v-deep {
  height: 100%;
  width: 100%;
  .editor {
    font-family: 'Lucida Console', monospace;
    resize: none;
    height: 100%;
    width: 100%;
    padding:5px;
    font-size:12px;
    line-height: 18px;
    white-space : pre;
    font-weight: 500;
  }
  .CodeMirror {
    height: 100%;
    width: 100%;
  }
}
</style>
