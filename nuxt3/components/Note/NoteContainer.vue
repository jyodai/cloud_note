<template>
  <div class="note">
    <note-tab />
    <div class="note-content">
      <div class="note-content-head">
        <note-content-head />
      </div>
      <div class="note-content-body">
        <template v-if="visible">
          <note-content-body
            v-if="note && note.note_type === $const.NOTE_TYPE_NORMAL"
            :note="note"
          />
          <task-content-body
            v-if="note && note.note_type === $const.NOTE_TYPE_TASK"
            :note="note"
          />
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import NoteTab from './NoteTab.vue';
import NoteContentHead from './NoteContentHead.vue';
import NoteContentBody from './NoteContentBody.vue';
import { useNoteTabStore } from '~/store/NoteTab';
import TaskContentBody from '~/components/Task/TaskContentBody.vue';

export default {
  components : {
    NoteTab,
    NoteContentHead,
    NoteContentBody,
    TaskContentBody,
  },
  data () {
    return {
      noteTabStore : useNoteTabStore(),
      note         : null,
      visible      : true,
    };
  },
  computed : {
    changeSelectNote () {
      return this.noteTabStore.getSelectNote;
    },
  },
  watch : {
    changeSelectNote (newVal) {
      this.note    = newVal;
      this.visible = false;
      this.$nextTick(() => {
        this.visible = true;
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.note {
  height: 100%;
  width: 100%;
  .note-content {
    width: 100%;
    height: calc(100% - 40px);
    box-shadow: inset 3px 0px 20px 1px rgb(0 0 0 / 45%);
    padding : 20px;
    .note-content-head {
      width: 100%;
      height: 40px;
    }
    .note-content-body {
      width: 100%;
      height: calc(100% - 40px);
    }
  }
}
</style>
