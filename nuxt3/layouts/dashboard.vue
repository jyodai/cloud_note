<template>
  <splitpanes class="dashboard">
    <pane
      id="mainPanel"
      class="main-panel"
    >
      <splitpanes horizontal>
        <pane size="100">
          <div class="content-area">
            <slot />
            <modal-list />
          </div>
        </pane>
      </splitpanes>
    </pane>
  </splitpanes>
</template>

<script lang="ts">
import { Splitpanes, Pane } from 'splitpanes';
import 'splitpanes/dist/splitpanes.css';
import ModalList from './ModalList.vue';

export default {
  components : {
    Splitpanes,
    Pane,
    ModalList,
  },
};
</script>

<style lang="scss" scoped>
.dashboard {
  height: 100vh;
  .main-panel {
    min-width: calc(100% - 250px);
    .content-area {
      background: $color-primary;
      height: 100%;
    }
  }
}
</style>
