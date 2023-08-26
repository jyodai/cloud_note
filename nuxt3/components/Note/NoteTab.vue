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

<script setup lang="ts">
import Draggable from 'vuedraggable';
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';
import { useUserStore } from '~/store/User';
import { useNoteTabStore } from '~/store/NoteTab';
import Note from '~/types/models/note';

const userStore    = useUserStore();
const noteTabStore = useNoteTabStore();

noteTabStore.initNoteTab();
noteTabStore.loadNoteTab(userStore.getUser);

const noteTab = ref(noteTabStore.getNoteTab);
watch(noteTab, (newNoteTabArray) => {
  noteTabStore.moveNoteTab(newNoteTabArray);
});

onMounted(() => {
  initSelectNote();
  document.addEventListener('keydown', handleKeyDown);
});

onBeforeUnmount(() => {
  document.removeEventListener('keydown', handleKeyDown);
});

function handleKeyDown(event: KeyboardEvent) {
  if (event.ctrlKey && event.key === 'j') {
    event.preventDefault();
    event.stopPropagation();
    noteTabStore.setPrevNote();
  }

  if (event.ctrlKey && event.key === 'k') {
    event.preventDefault();
    event.stopPropagation();
    noteTabStore.setNextNote();
  }
}

function initSelectNote() {
  const currentNoteTab = noteTabStore.getNoteTab;
  if (currentNoteTab.length === 0) {
    return;
  }
  noteTabStore.setSelectNote(currentNoteTab[0]);
}

function setNote(note: Note) {
  noteTabStore.setSelectNote(note);
}

function removeNoteTab(id: number) {
  noteTabStore.removeNoteTab(id);
}

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
