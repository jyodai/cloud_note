<template>
  <splitpanes class="note">
    <pane size="15">
      <dir-frame />
    </pane>
    <pane>
      <note-frame />
    </pane>
  </splitpanes>
</template>

<script>
import { Splitpanes, Pane } from 'splitpanes'
import 'splitpanes/dist/splitpanes.css'
import NoteFrame from '../components/Note/NoteContainer.vue'
import DirFrame from '../components/directory/DirFrame.vue'

export default {
  components: {
    Splitpanes,
    Pane,
    DirFrame,
    NoteFrame,
  },
  layout: 'dashboard',
  data () {
    return {
    }
  },
  mounted () {
  },
  methods: {
  },
}
</script>

<style lang="scss" scoped>
.note {
}
</style>
