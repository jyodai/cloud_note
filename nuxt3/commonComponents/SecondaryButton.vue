<template>
  <v-btn
    class="button"
    @click="click"
  >
    {{ label }}
  </v-btn>
</template>

<script setup lang="ts">
defineProps({
  label : {
    type     : String,
    required : true,
  }
});

const emits = defineEmits([
  'click'
]);

function click() {
  emits('click');
}
</script>

<style lang="scss" scoped>
.button {
  background: $color-button-secondary;
  color: $color-text-primary;
  border: 1px solid $color-border;
}
</style>
