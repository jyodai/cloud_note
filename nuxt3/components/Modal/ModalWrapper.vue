<template>
  <div
    ref="modal"
    class="modal"
  >
    <vue-final-modal
      class="modal-container"
      content-class="modal-content"
      :modal-id="modalName"
      :teleport-to="false"
      @before-open="option.beforeOpen"
      @opened="option.opened"
      @before-close="option.beforeClose"
      @closed="option.closed"
    >
      <template v-if="option.layoutType === 'normal'">
        <div class="normal-layout">
          <span class="modal__title">
            <slot name="modalTitle" />
          </span>
          <div class="modal__content">
            <slot name="modalContent" />
          </div>
          <div class="modal__action">
            <slot name="modalAction" />
          </div>
        </div>
      </template>

      <template v-if="option.layoutType === 'split'">
        <div class="split-layout">
          <div class="modal__sidebar">
            <slot name="modalSidebar" />
          </div>
          <div class="modal__content">
            <slot name="modalContent" />
          </div>
        </div>
      </template>
    </vue-final-modal>
  </div>
</template>

<script setup lang="ts">
import { VueFinalModal } from 'vue-final-modal';
import { ref, Ref } from 'vue';

interface ModalOption {
  width : string;
  height : string;
  beforeOpen : () => void;
  opened : () => void;
  beforeClose : () => void;
  closed : () => void;
  layoutType: 'normal' | 'split';
}

const props = defineProps({
  modalName : {
    type     : String,
    required : true,
  },
  modalOption : {
    type    : Object,
    default : () => { return {}; },
  },
});

const defaultOption       = {
  width       : '60%',
  height      : '60%',
  beforeOpen  : () => { return; },
  opened      : () => { return; },
  beforeClose : () => { return; },
  closed      : () => { return; },
  layoutType  : 'normal',
};
const option: ModalOption = Object.assign(defaultOption, props.modalOption) as ModalOption;

const width: Ref<string>  = ref('');
const height: Ref<string> = ref('');
width.value               = option.width;
height.value              = option.height;

</script>

<style lang="scss" scoped>
.modal {
  height: 100%;
  width: 100%;
  :deep(.vfm--overlay) {
    background-color: $color-overlay;
  }
  :deep(.modal-container) {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  :deep(.modal-content) {
    width: v-bind(width);
    height: v-bind(height);
    max-height: 90%;
    border: 1px solid;
    border-radius: 0.25rem;
    border-color: $color-primary-light;
    background-color: $color-primary;
  }

  .normal-layout {
    height : 100%;
    margin: 0 1rem;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    position: relative;
    .modal__title {
      margin: 0 2rem 0 0;
      font-size: 1.5rem;
      font-weight: 700;
    }
    .modal__content {
      flex-grow: 1;
      overflow-y: auto;
    }
    .modal__action {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-shrink: 0;
      padding: 1rem 0 0;
    }
  }

  .split-layout {
    height : 100%;
    display: flex;
    flex-direction: row;
    .modal__sidebar {
      width: 200px;
      background: $color-primary-light;
      padding: 20px 10px;
      overflow-y: auto;
      border-right: 1px solid $color-border;
    }
    .modal__content {
      flex-grow: 1;
      background: $color-primary;
      padding: 20px 20px;
      overflow-y: auto;
    }
  }
}
</style>
