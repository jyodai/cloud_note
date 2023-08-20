<template>
  <draggable
    v-model="noteTab"
    item-key="id"
    class="note-tab thin-scroll-bar"
  >
    <template #item="{ element }">
      <div
        class="note-title g-up-down-center"
        :class="{select : element.id === noteTabStore.getSelectNoteId}"
      >
        <span
          class="title"
          :class="{'select-title' : element.id === noteTabStore.getSelectNoteId}"
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
import { useUserStore } from '~/store/User';
import { useNoteTabStore } from '~/store/NoteTab';

export default {
  components : {
    Draggable,
  },
  data () {
    return {
      userStore    : useUserStore(),
      noteTabStore : useNoteTabStore(),
    };
  },
  computed : {
    noteTab : {
      get () {
        return this.noteTabStore.getNoteTab;
      },
      set (noteTabArray) {
        this.noteTabStore.moveNoteTab(noteTabArray);
      },
    },
  },
  created () {
    this.noteTabStore.initNoteTab();
    this.noteTabStore.loadNoteTab(this.userStore.user);
  },
  mounted () {
    this.initSelectNote();
    document.addEventListener('keydown', this.handleKeyDown);
  },
  beforeUnmount() {
    document.removeEventListener('keydown', this.handleKeyDown);
  },
  methods : {
    handleKeyDown(event) {
      if (event.ctrlKey && event.key === 'j') {
        event.preventDefault();
        event.stopPropagation();
        this.noteTabStore.setPrevNote();
      }

      if (event.ctrlKey && event.key === 'k') {
        event.preventDefault();
        event.stopPropagation();
        this.noteTabStore.setNextNote();
      }
    },
    initSelectNote () {
      const noteTab = this.noteTabStore.getNoteTab;
      if (noteTab.length === 0) {
        return;
      }
      this.noteTabStore.setSelectNote(noteTab[0]);
    },
    setNote (note) {
      this.noteTabStore.setSelectNote(note);
    },
    removeNoteTab (id) {
      this.noteTabStore.removeNoteTab(id);
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
  border-bottom: 1px solid $color-border;
  color: $color-text-primary-dark;
  .note-title {
    white-space: nowrap;
    height: 100%;
    border-right: 1px solid $color-border;
    padding: 0px 10px;
    cursor: pointer;
    .title {
      margin-right: 5px;
    }
    .title:hover {
      background: $color-hover; 
    }
    .close-icon:hover {
      background: $color-hover;
    }
  }
  .select {
    color: $color-text-primary;
  }
  .select-title {
    background: $color-select;
  }
}

::-webkit-scrollbar {
  width: 4px;
  height: 4px;
}
</style>
