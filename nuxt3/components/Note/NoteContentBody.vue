<template>
  <div
    v-if="content !== null && showMarkdown === false"
    class="content"
  >
    <markdown-edit
      class="content-edit"
      :content="content"
      @save-note="saveNote"
      @blur="blur"
    />
    <markdown-view
      ref="markdownView"
      :content="content"
    />
  </div>
  <div
    v-else-if="content !== null && showMarkdown === true"
    class="content"
  >
    <markdown-view
      ref="markdownView"
      :content="content"
    />
  </div>
</template>

<script>

import MarkdownView from '~/commonComponents/MarkdownView.vue';
import MarkdownEdit from '~/commonComponents/MarkdownEdit.vue';
import Html2Pdf from '~/libraries/html2Pdf.js';

export default {
  components : {
    MarkdownView,
    MarkdownEdit,
  },
  props : {
    note : {
      type    : Object,
      default : () => { return {}; },
    },
  },
  data () {
    return {
      showMarkdown : true,
    };
  },
  computed : {
    content () {
      return this.$store.getters['NoteContent/getSelectContent'];
    },
  },
  watch : {
    async note (newVal) {
      await this.load(newVal);
    },
  },
  async created () {
    await this.load(this.note);
  },
  mounted() {
    document.addEventListener('keydown', this.handleKeyDown);
  },
  beforeUnmount() {
    document.removeEventListener('keydown', this.handleKeyDown);
  },
  methods : {
    async load (note) {
      await this.$store.dispatch('NoteContent/loadSelectContent', { noteId : note.id, });
    },
    handleKeyDown(event) {
      if (event.ctrlKey && event.key === 'e') {
        event.preventDefault();
        event.stopPropagation();
        this.changeEditor();
      }

      if (event.ctrlKey && event.key === 'p') {
        event.preventDefault();
        event.stopPropagation();
        this.outputPdf();
      }
    },
    changeEditor () {
      if (this.$store.getters['NoteContent/getSelectNoteId'] === null) {
        alert('ファイルが選択されていません');
        return;
      }
      this.showMarkdown = !this.showMarkdown;
    },
    blur (data) {
      this.saveNote(data);
    },
    saveNote (data) {
      this.$store.dispatch('NoteContent/updateSelectContent', data);
    },
    outputPdf () {
      const element = this.$refs.markdownView.$el.parentNode;
      const pdf     = new Html2Pdf(element);
      pdf.setCssClass('g-markdown-print');
      pdf.output();
    },
  },
};
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
