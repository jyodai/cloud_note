<template>
  <div class="title-area thin-scroll-bar">
    <span><v-icon size="14">mdi-folder</v-icon></span>
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
      this.getNotePath(newVal);
    },
  },
  methods : {
    async getNotePath (note) {
      if (!note) {
        this.notePath = 'ファイル未選択';
        return;
      }
      const url      = this.$config.public.apiUrl + '/notes/' + note.id;
      const response = await this.$axios.get(url);
      this.notePath  = response.path;
    },
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
