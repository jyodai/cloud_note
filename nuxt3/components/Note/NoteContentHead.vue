<template>
  <div class="title-area thin-scroll-bar">
    <span @click="openNoteFile()"><v-icon size="12">mdi-folder</v-icon></span>
    <span>{{ notePath }}</span>
  </div>
</template>

<script>

export default {
  components : {
  },
  data () {
    return {
      notePath : 'ファイル未選択',
    };
  },
  computed : {
    changeSelectNote () {
      return this.$store.getters['NoteTab/getSelectNote'];
    },
  },
  watch : {
    changeSelectNote (newVal) {
      this.getNotePath(newVal.id);
    },
  },
  methods : {
    async getNotePath (noteId) {
      if (!noteId) {
        this.notePath = 'ファイル未選択';
        return;
      }
      const url      = this.$config.public.apiUrl + '/notes?noteId=' + noteId;
      const response = await this.$axios.get(url);
      this.notePath  = response.path;
    },
    openNoteFile () {
      let noteId = null;
      noteId     = this.$store.getters['NoteContent/getSelectNoteId'];
      if (!noteId) {
        return alert('ファイルが開いていません');
      }
      const url = process.env.SERVER_ALIAS + '/noteFile?noteId=' + noteId;
      window.open(url, 'sub', 'top=100,left=300,width=600,height=500');
    },
  },
};
</script>

<style lang="scss" scoped>
.title-area {
  width: 100%;
  text-align: left;
  color: #888888;
  font-size: 12px;
  white-space: nowrap;
  overflow: auto;
}
</style>
