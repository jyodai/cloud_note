<template>
  <splitpanes class="note">
    <pane :size="sidebarSize">
      <side-bar />
    </pane>
    <pane :size="noteFrameSize">
      <note-frame />
    </pane>
  </splitpanes>
</template>

<script setup>
import { ref } from 'vue';
import { useUserStore } from '~/store/User';
import ShortcutKey from '~/libraries/shortcutKey';
import { useKeydown } from '~/composable/useKeydown';

definePageMeta({
  layout : 'dashboard',
});

const userStore = useUserStore();
await userStore.setUser();

const sidebarSize   = ref(20);
const noteFrameSize = ref(80);

function toggleSidebarr() {
  sidebarSize.value   = sidebarSize.value === 0 ? 20 : 0;
  noteFrameSize.value = sidebarSize.value === 0 ? 100 : 80;
}

registerShortcut();
function registerShortcut() {
  const toggleSidebar = new ShortcutKey('t', toggleSidebarr);
  useKeydown(toggleSidebar.handleKeyDown);
}
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

