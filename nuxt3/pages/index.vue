<template>
  <splitpanes class="note">
    <pane size="20">
      <side-bar />
    </pane>
    <pane>
      <note-frame />
    </pane>
  </splitpanes>
</template>

<script setup>
import { useUserStore } from '~/store/User';

definePageMeta({
  layout : 'dashboard',
});

const userStore = useUserStore();
await userStore.setUser();
</script>

<script>
import { Splitpanes, Pane } from 'splitpanes';
import 'splitpanes/dist/splitpanes.css';
import NoteFrame from '../components/Note/NoteContainer.vue';
import SideBar from '../components/SideBar/SideBar.vue';

export default {
  components : {
    Splitpanes,
    Pane,
    NoteFrame,
    SideBar,
  },
};
</script>

<style lang="scss" scoped>
.note {
}
</style>

