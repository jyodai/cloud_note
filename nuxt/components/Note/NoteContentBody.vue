<template>
  <div
    v-if="note !== null && showMarkdown === false"
    class="content"
    @click.ctrl="changeEditor()"
  >
    <markdown-edit
      class="content-edit"
      :note="note"
      @saveNote="saveNote"
      @blur="blur"
    />
    <markdown-view
      class="content-preview"
      :note="note"
    />
  </div>
  <div
    v-else-if="note !== null && showMarkdown === true"
    class="content"
    @click.ctrl="changeEditor()"
  >
    <markdown-view
      :note="note"
    />
  </div>
</template>

<script>

import MarkdownView from '~/commonComponents/MarkdownView.vue'
import MarkdownEdit from '~/commonComponents/MarkdownEdit.vue'

export default {
  components: {
    MarkdownView,
    MarkdownEdit,
  },
  props: {
    note: {
      type    : Object,
      default : () => {},
    },
  },
  data () {
    return {
      showMarkdown: true,
    }
  },
  methods: {
    changeEditor () {
      if (this.$store.getters['NoteContent/getSelectNoteId'] === null) {
        alert('ファイルが選択されていません')
        return
      }
      this.showMarkdown = !this.showMarkdown
    },
    blur (data) {
      this.saveNote(data)
    },
    saveNote (data) {
      this.$store.dispatch('NoteContent/updateSelectContent', data)
    },
  },
}
</script>

<style lang="scss" scoped>
.content {
  width: 100%;
  height: 100%;
  flex-grow: 1;
  .content-edit {
    width: 50%;
    float: left;
  }
  .content-preview {
    width: 50%;
  }
}
</style>
