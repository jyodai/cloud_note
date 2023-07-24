<template>
  <div
    ref="modal"
    class="modal"
  >
    <vue-final-modal
      v-model="showModal"
      class="modal-container"
      content-class="modal-content"
      :modal-id="modalName"
      :teleport-to="false"
      @before-open="option.beforeOpen"
      @opened="option.opened"
    >
      <span class="modal__title">
        <slot name="modalTitle" />
      </span>
      <div class="modal__content">
        <slot name="modalContent" />
      </div>
      <div class="modal__action">
        <slot name="modalAction" />
      </div>
    </vue-final-modal>
  </div>
</template>

<script>
import { VueFinalModal } from 'vue-final-modal';

export default {
  components : {
    VueFinalModal,
  },
  props : {
    modalName : {
      type    : String,
      default : '',
    },
    modalOption : {
      type    : Object,
      default : () => { return {}; },
    },
  },
  data () {
    return {
      showModal : false,
      option    : null,
      width     : "",
      height    : "",
    };
  },
  created () {
    this.setOption();
  },
  methods : {
    setOption () {
      const defaultOption = {
        width      : '60%',
        height     : '60%',
        beforeOpen : () => { return; },
        opened     : () => { return; },
      };
      this.option         = Object.assign(defaultOption, this.modalOption);
      this.width          = this.option.width;
      this.height         = this.option.height;
    },
  },
};
</script>

<style lang="scss" scoped>
.modal {
  height: 100%;
  width: 100%;
  :deep(.modal-container) {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  :deep(.modal-content) {
    width: v-bind(width);
    height: v-bind(height);
    position: relative;
    display: flex;
    flex-direction: column;
    max-height: 90%;
    margin: 0 1rem;
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.25rem;
    border-color: #363636;
    background-color: #333333;
  }
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
</style>
