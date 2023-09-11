<template>
  <div class="draw-canvas">
    <div class="head">
      <input
        id="draw"
        type="radio"
        name="mode"
        checked
        @click="canvas.changeDrawingMode(true)"
      >
      <label
        for="draw"
        class="mr-1"
      >
        ペン
      </label>
      <input
        id="select"
        type="radio"
        name="mode"
        @click="canvas.changeDrawingMode(false)"
      >
      <label
        for="select"
        class="mr-2"
      >
        選択
      </label>

      <v-icon
        id="zoom-out"
        size="20"
        @click="canvas.zoomOut()"
      >
        mdi-minus
      </v-icon>
      <v-icon
        id="zoom-in"
        size="20"
        class="mr-2"
        @click="canvas.zoomIn()"
      >
        mdi-plus
      </v-icon>

      <v-icon
        id="undo"
        size="20"
        @click="canvas.undo()"
      >
        mdi-arrow-left
      </v-icon>
      <v-icon
        id="redo"
        size="20"
        class="mr-2"
        @click="canvas.redo()"
      >
        mdi-arrow-right
      </v-icon>

      <v-icon
        id="remove"
        size="20"
        class="mr-2"
        @click="canvas.remove()"
      >
        mdi-trash-can-outline
      </v-icon>

      <v-icon
        id="clear"
        size="20"
        class="mr-2"
        @click="canvas.clear()"
      >
        mdi-refresh
      </v-icon>
    </div>
    <div class="body">
      <canvas
        id="canvas"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import Fabric from '~/libraries/fabric';

let canvas = null;

onMounted(() => {
  const id = "canvas";
  canvas   = new Fabric(id);
});
</script>
<style lang="scss" scoped>
.draw-canvas {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  .head {
    width: 100%;
  }
  .body {
    width: 100%;
    height: 100%;
    flex-grow: 1;
    overflow: auto;
  }
}
</style>
