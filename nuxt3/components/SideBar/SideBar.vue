<template>
  <div class="side-bar">
    <div class="menu">
      <side-menu />
    </div>
    <div class="dir">
      <dir-frame />
    </div>
  </div>
</template>

<script setup lang="ts">
import SideMenu from '~/components/SideMenu/SideMenu.vue';
import DirFrame from '~/components/directory/DirFrame.vue';
</script>

<style lang="scss" scoped>
.side-bar {
  width: 100%;
  height: 100%;
  .menu {
    width : 100%;
    height : 175px;
  }
  .dir {
    width : 100%;
    height : calc(100% - 175px);
  }
}
</style>
