<template>
  <div ref="modal" class="modal">
    <vue-final-modal
      v-model="showModal"
      classes="modal-container"
      content-class="modal-content"
      :name="modalName"
      @before-open="option.beforeOpen"
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

export default {
  components: {
  },
  props: {
    modalName: {
      type    : String,
      default : '',
    },
    modalOption: {
      type    : Object,
      default : () => {},
    },
  },
  data () {
    return {
      showModal : false,
      option    : null,
    }
  },
  created () {
    this.setOption()
  },
  mounted () {
    this.setSize()
  },
  methods: {
    setOption () {
      const defaultOption = {
        width      : '650px',
        height     : '500px',
        beforeOpen : () => {},
      }
      this.option = Object.assign(defaultOption, this.modalOption)
    },
    setSize () {
      const componentElement = this.$refs.modal
      const element = componentElement.getElementsByClassName('modal-content')
      element[0].style.width = this.option.width
      element[0].style.height = this.option.height
    },
  },
}
</script>

<style lang="scss" scoped>
.modal {
  height: 100%;
  width: 100%;
  ::v-deep .modal-container {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  ::v-deep .modal-content {
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
    padding: 0px 10px;
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
