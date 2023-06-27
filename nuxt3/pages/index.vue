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

<script setup>
setPageLayout("dashboard");
</script>

<script>
import { Splitpanes, Pane } from 'splitpanes';
import 'splitpanes/dist/splitpanes.css';
import NoteFrame from '../components/Note/NoteContainer.vue';
import DirFrame from '../components/directory/DirFrame.vue';

export default {
  components : {
    Splitpanes,
    Pane,
    DirFrame,
    NoteFrame,
  },
};
</script>

<style lang="scss" scoped>
.note {
}
</style>

