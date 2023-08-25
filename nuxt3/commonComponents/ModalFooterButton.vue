<template>
  <div>
    <span
      v-for="(button, index) in buttonLists"
      :key="index"
    >
      <template v-if="isVisible(button.name)">
        <v-btn
          class="modal-footer-button"
          @click="button.event()"
        >
          {{ button.label }}
        </v-btn>
      </template>
    </span>
  </div>
</template>

<script setup lang="ts">
interface ButtonItem {
  name: string;
  label: string;
  event: () => void;
}

const props = defineProps({
  visibleLists : {
    type    : Array,
    default : () => [],
  },
});

const emits = defineEmits([
  'select',
  'save',
  'delete',
  'close',
]);

const buttonLists: Array<ButtonItem> = [
  {
    name  : 'select',
    label : '選択',
    event : () => emits('select'),
  },
  {
    name  : 'save',
    label : '保存',
    event : () => emits('save'),
  },
  {
    name  : 'delete',
    label : '削除',
    event : () => emits('delete'),
  },
  {
    name  : 'close',
    label : '閉じる',
    event : () => emits('close'),
  },
];

function isVisible (name: string): boolean {
  return props.visibleLists.includes(name);
}
</script>

<style lang="scss" scoped>
.modal-footer-button {
  margin-right : 10px;
}
</style>
