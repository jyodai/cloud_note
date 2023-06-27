<template>
  <draggable
    v-model="noteTab"
    item-key="id"
    class="note-tab thin-scroll-bar"
  >
    <template #item="{ element }">
      <div
        class="note-title g-up-down-center"
        :class="{select : element.id === $store.getters['NoteTab/getSelectNoteId']}"
      >
        <span
          class="title"
          @click="setNote(element)"
        >{{ element.title }}</span>
        <v-icon
          class="close-icon"
          size="14"
          @click="removeNoteTab(element.id)"
        >
          mdi-close-thick
        </v-icon>
      </div>
    </template>
  </draggable>
</template>

<script>
import Draggable from 'vuedraggable';

export default {
  components : {
    Draggable,
  },
  data () { return {}; },
  computed : {
    noteTab : {
      get () {
        return this.$store.getters['NoteTab/getNoteTab'];
      },
      set (noteTabArray) {
        this.$store.dispatch('NoteTab/moveNoteTab', noteTabArray);
      },
    },
  },
  created () {
    this.$store.dispatch('NoteTab/initNoteTab');
    this.$store.dispatch('NoteTab/loadNoteTab');
  },
  mounted () {
    this.initSelectNote();
  },
  methods : {
    initSelectNote () {
      const noteTab = this.$store.getters['NoteTab/getNoteTab'];
      if (noteTab.length === 0) {
        return;
      }
      this.$store.dispatch('NoteTab/setSelectNote', noteTab[0]);
    },
    setNote (note) {
      this.$store.dispatch('NoteTab/setSelectNote', note);
    },
    removeNoteTab (id) {
      const nextNote = this.getNextNote(id);
      this.$store.dispatch('NoteTab/removeNoteTab', id);

      if (id === this.$store.getters['NoteTab/getSelectNoteId']) {
        this.$store.dispatch('NoteTab/unsetSelectNote');
        if (nextNote !== null) {
          this.$store.dispatch('NoteTab/setSelectNote', nextNote);
        }
      }
    },
    getNextNote (id) {
      const noteTab = this.$store.getters['NoteTab/getNoteTab'];
      const index   = noteTab.findIndex(note => note.id === id);
      let nextNote  = noteTab[index - 1] ? noteTab[index - 1] : null;
      if (nextNote === null && noteTab.length > 1) {
        nextNote = noteTab[1];
      }
      return nextNote;
    },
  },
};
</script>

<style lang="scss" scoped>
.note-tab {
  font-size: 12px;
  overflow: auto;
  width: 100%;
  height: 40px;
  display: flex;
  background: #333333;
  .note-title {
    white-space: nowrap;
    height: 100%;
    border-right: 1px solid #444444;
    padding: 0px 10px;
    cursor: pointer;
    .title {
      margin-right: 5px;
    }
    .title:hover {
      background: #666666;
    }
    .close-icon:hover {
      background: #666666;
    }
  }
  .select {
    background: #222222;
  }
}
</style>
