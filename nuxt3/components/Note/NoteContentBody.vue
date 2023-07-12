<template>
  <template v-if="visible">
    <div
      v-if="content !== null && !showMarkdown"
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
        class="content-preview"
        :content="content"
      />
    </div>
    <div
      v-else-if="content !== null && showMarkdown"
      class="content"
    >
      <markdown-view
        ref="markdownView"
        :content="content"
      />
    </div>
  </template>
</template>

<script lang="ts">

import { CreateComponentPublicInstance } from 'vue';
import MarkdownView from '~/commonComponents/MarkdownView.vue';
import MarkdownEdit from '~/commonComponents/MarkdownEdit.vue';
import Html2Pdf from '~/libraries/html2Pdf';
import Note from '~/types/models/note';
import NoteContent from '~/types/models/noteContent';

export default {
  components : {
    MarkdownView,
    MarkdownEdit,
  },
  props : {
    note : {
      type     : Object as () => Note,
      required : true,
    },
  },
  data () {
    return {
      visible      : false,
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
    async load (note: Note) {
      this.visible = false;
      await this.$store.dispatch('NoteContent/loadSelectContent', { noteId : note.id, });
      this.visible = true;
    },
    handleKeyDown(event: KeyboardEvent) {
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
    blur (data: NoteContent) {
      this.saveNote(data);
    },
    saveNote (data: NoteContent) {
      this.$store.dispatch('NoteContent/updateSelectContent', data);
    },
    outputPdf () {
      const markdonwView         = this.$refs.markdownView as CreateComponentPublicInstance;
      const element: HTMLElement = markdonwView.$el.parentNode as HTMLElement;
      const fileName             = this.note.title;
      const pdf                  = new Html2Pdf(element, fileName);
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
