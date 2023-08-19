<template>
  <div class="title-area thin-scroll-bar">
    <span class="mr-1"><v-icon size="14">mdi-folder</v-icon></span>
    <span>{{ notePath }}</span>
  </div>
</template>

<script>
import { useNoteTabStore } from '~/store/NoteTab';

export default {
  components : {
  },
  data () {
    return {
      noteTabStore : useNoteTabStore(),
      notePath     : 'ファイル未選択',
    };
  },
  computed : {
    changeSelectNote () {
      return this.noteTabStore.getSelectNote;
    },
  },
  watch : {
    changeSelectNote (newVal) {
      this.changeNotePath(newVal);
    },
  },
  methods : {
    changeNotePath (note) {
      if (!note) {
        this.notePath = 'ファイル未選択';
      } else {
        this.setNotePath(note.id);
      }
    },
    async setNotePath (noteId) {
      const url      = this.$config.public.apiUrl + '/notes/' + noteId;
      const response = await this.$axios.get(url);
      const note     = response.data;
      this.notePath  = this.$util.note.convertPath(note);
    }
  },
};
</script>

<style lang="scss" scoped>
.title-area {
  width: 100%;
  text-align: left;
  color: #888888;
  font-size: 14px;
  white-space: nowrap;
  overflow: auto;
}
</style>
