<template>
  <div>
    <span
      v-for="(button, index) in buttonLists"
      :key="index"
    >
      <template v-if="isVisible(button.name)">
        <primary-button
          v-if="button.type === 'primary'"
          class="modal-footer-button"
          :label="button.label"
          @click="button.event()"
        />
        <secondary-button
          v-else-if="button.type === 'secondary'"
          class="modal-footer-button"
          :label="button.label"
          @click="button.event()"
        />
      </template>
    </span>
  </div>
</template>

<script setup lang="ts">
import PrimaryButton from '~/commonComponents/PrimaryButton.vue';
import SecondaryButton from '~/commonComponents/SecondaryButton.vue';

interface ButtonItem {
  name: string;
  label: string;
  event: () => void;
  type: 'primary' | 'secondary';
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
    type  : 'primary',
  },
  {
    name  : 'save',
    label : '保存',
    event : () => emits('save'),
    type  : 'primary',
  },
  {
    name  : 'delete',
    label : '削除',
    event : () => emits('delete'),
    type  : 'primary',
  },
  {
    name  : 'close',
    label : '閉じる',
    event : () => emits('close'),
    type  : 'secondary',
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
