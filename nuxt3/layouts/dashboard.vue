<template>
  <splitpanes class="dashboard">
    <pane
      id="sidePanel"
      size="0"
      class="side-panel"
    >
      <side-menu />
    </pane>
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

<script>
import { Splitpanes, Pane } from 'splitpanes'
import 'splitpanes/dist/splitpanes.css'
import ModalList from './ModalList'
import SideMenu from '~/components/SideMenu/SideMenu.vue'

export default {
  components : {
    Splitpanes,
    Pane,
    SideMenu,
    ModalList,
  },
  data () {
    return {
    }
  },
  mounted () {
  },
  methods : {
  },
}
</script>

<style lang="scss" scoped>
.dashboard {
  height: 100vh;
  .side-panel {
    height: 100vh;
    min-width: 50px;
    max-width: 250px;
    transition: all 0.1s;
  }
  .main-panel {
    min-width: calc(100% - 250px);
    .content-area {
      background: #222;
      height: 100%;
      box-shadow: inset 3px 0px 20px 1px rgb(0 0 0 / 45%);
    }
  }
}
</style>
