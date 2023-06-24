<template>
  <div class="note">
    <note-tab />
    <div class="note-content">
      <div class="note-content-head">
        <note-content-head />
      </div>
      <div class="note-content-body">
        <note-content-body
          v-if="note && note.note_type === $const.NOTE_TYPE_NORMAL"
          :note="note"
        />
        <div
          v-if="note && note.note_type === $const.NOTE_TYPE_TASK"
        >
          Sample Task
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import NoteTab from './NoteTab.vue'
import NoteContentHead from './NoteContentHead.vue'
import NoteContentBody from './NoteContentBody.vue'

export default {
  components : {
    NoteTab,
    NoteContentHead,
    NoteContentBody,
  },
  data () {
    return {
      note : null,
    }
  },
  computed : {
    changeSelectNote () {
      return this.$store.getters['NoteTab/getSelectNote']
    },
  },
  watch : {
    changeSelectNote (newVal) {
      this.note = newVal
    },
  },
  mounted () {},
  methods : {},
}
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
