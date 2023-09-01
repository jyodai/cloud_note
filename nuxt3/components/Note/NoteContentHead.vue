<template>
  <div class="title-area thin-scroll-bar">
    <div class="left">
      <span class="mr-1"><v-icon size="14">mdi-folder</v-icon></span>
      <span>{{ notePath }}</span>
    </div>
    <div class="right">
      <icon-list
        v-if="noteTabStore.getSelectNote.note_type === $const.NOTE_TYPE_NORMAL"
        :show-icons="['edit']"
        @edit="edit()"
      />
    </div>
  </div>
</template>

<script>
import { useNoteTabStore } from '~/store/NoteTab';
import IconList from '~/commonComponents/IconList.vue';
import { useNoteContentStore } from '~/store/NoteContent';

export default {
  components : {
    IconList,
  },
  data () {
    return {
      noteContentSotre : useNoteContentStore(),
      noteTabStore     : useNoteTabStore(),
      notePath         : 'ファイル未選択',
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
    },
    edit() {
      this.noteContentSotre.toggleShowMarkdown();
    },
  },
};
</script>

<style lang="scss" scoped>
.title-area {
  width: 100%;
  text-align: left;
  font-size: 14px;
  white-space: nowrap;
  overflow: auto;
  color: $color-text-primary-dark;
  display: flex;
  .right {
    margin-left : auto;
    &:hover {
      background: $color-hover;
    }
  }
}
</style>
