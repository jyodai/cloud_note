<template>
  <template
    v-for="(icon, index) in icons"
    :key="index"
  >
    <v-icon
      v-if="showIcons.includes(icon.key)"
      size="20"
      class="icon"
      @click="icon.event()"
    >
      {{ icon.icon }}
    </v-icon>
  </template>
</template>

<script setup lang="ts">
defineProps({
  showIcons : {
    type     : Array as PropType<Array<string>>,
    required : true,
  },
});

const emits = defineEmits(['copy', 'edit', 'lock', 'trash']);

const icons = [
  {
    key   : 'copy',
    icon  : 'mdi-content-copy',
    event : () => emits("copy"),
  },
  {
    key   : 'edit',
    icon  : 'mdi-pencil-outline',
    event : () => emits("edit"),
  },
  {
    key   : 'lock',
    icon  : 'mdi-lock-outline',
    event : () => emits("lock"),
  },
  {
    key   : 'trash',
    icon  : 'mdi-trash-can-outline',
    event : () => emits("trash"),
  },
];
</script>

<style lang="scss" scoped>
.icon {
  margin-right : 5px;
}
</style>
