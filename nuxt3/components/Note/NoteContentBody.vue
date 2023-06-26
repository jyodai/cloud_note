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
      class="content-preview"
      :content="content"
    />
  </div>
  <div
    v-else-if="content !== null && showMarkdown === true"
    class="content"
  >
    <markdown-view
      :content="content"
    />
  </div>
</template>

<script>

import MarkdownView from '~/commonComponents/MarkdownView.vue';
import MarkdownEdit from '~/commonComponents/MarkdownEdit.vue';

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
