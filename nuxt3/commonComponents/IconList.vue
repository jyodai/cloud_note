<template>
  <template
    v-for="(icon, index) in icons"
    :key="index"
  >
    <v-icon
      v-if="showIcons.includes(icon.key)"
      :size="size"
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
  size : {
    type    : Number,
    default : 20,
  }
});

const emits = defineEmits([
  'copy',
  'add',
  'edit',
  'commentEdit',
  'calendar',
  'lock',
  'trash',
  'preview',
  'noPreview',
]);

const icons = [
  {
    key   : 'copy',
    icon  : 'mdi-content-copy',
    event : () => emits("copy"),
  },
  {
    key   : 'add',
    icon  : 'mdi-plus',
    event : () => emits("add"),
  },
  {
    key   : 'edit',
    icon  : 'mdi-pencil-outline',
    event : () => emits("edit"),
  },
  {
    key   : 'commentEdit',
    icon  : 'mdi-comment-edit-outline',
    event : () => emits("commentEdit"),
  },
  {
    key   : 'calendar',
    icon  : 'mdi-calendar-month',
    event : () => emits('calendar'),
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
  {
    key   : 'preview',
    icon  : 'mdi-eye-outline',
    event : () => emits("preview"),
  },
  {
    key   : 'noPreview',
    icon  : 'mdi-eye-off-outline',
    event : () => emits("preview"),
  },
];
</script>

<style lang="scss" scoped>
.icon {
  margin-right : 5px;
}
</style>
