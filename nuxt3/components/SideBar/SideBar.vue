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
  color: $color-text-primary-dark;
  .menu {
    width : 100%;
    height : 125px;
  }
  .dir {
    width : 100%;
    height : calc(100% - 125px);
  }
}
</style>
