<template>
  <splitpanes
    class="note"
    @resized="resized($event)"
  >
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

const sidebarSize     = ref(20);
const noteFrameSize   = ref(80);
const lastSidebarSize = ref(20);

function toggleSidebarr() {
  if (isOpenedSidebar()) {
    sidebarSize.value   = 0;
    noteFrameSize.value = 100;
  } else {
    sidebarSize.value   = lastSidebarSize.value;
    noteFrameSize.value = 100 - lastSidebarSize.value;
  }
}

function isOpenedSidebar() {
  return sidebarSize.value !== 0;
}

function resized(event) {
  lastSidebarSize.value = event[0].size;
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

