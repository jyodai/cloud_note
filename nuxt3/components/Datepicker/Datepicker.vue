<template>
  <div class="datepicker">
    <modal
      :modal-name="modalName"
      :modal-option="modalOption"
    >
      <template #modalTitle />
      <template
        #modalContent
      >
        <div class="content">
          <VueDatePicker
            v-model="inputDate"
            format="yyyy-MM-dd"
            locale="ja"
            model-type="yyyy-MM-dd"
            week-start="0"
            :enable-time-picker="false"
            auto-apply
            hide-offset-dates
            :inline="true"
            dark
          />
        </div>
      </template>
      <template #modalAction>
        <modal-footer-button
          :visible-lists="['select', 'close']"
          @select="select"
          @close="closeModal"
        />
      </template>
    </modal>
  </div>
</template>

<script setup lang="ts">
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { ref, Ref } from 'vue';
import Modal from '../Modal/ModalWrapper.vue';
import ModalFooterButton from '~/commonComponents/ModalFooterButton.vue';
import ThrowDatepicker from '~/types/modals/throwDatepicker';

const nuxtApp = useNuxtApp();

const modalName = 'Datepicker';

const modalOption = {
  beforeOpen : init,
  width      : '350px',
  height     : '420px',
};

const inputDate: Ref<string|null> = ref(null);

const throwDatepicker: ThrowDatepicker = {
  selectDate : null,
};

function init(): void {
  inputDate.value = '';
}

function select(): void {
  throwDatepicker.selectDate = inputDate.value ? inputDate.value : null;
  nuxtApp.$vfm.setThrowData(modalName, throwDatepicker);
  closeModal(nuxtApp.$const.MODAL_CLOSE_TYPE_SELECT);
}

function closeModal(type: number = nuxtApp.$const.MODAL_CLOSE_TYPE_CLOSE): void {
  nuxtApp.$vfm.close(modalName, type);
}


</script>

<style lang="scss" scoped>
.datepicker {
  height: 100%;
  width: 100%;
  .content {
    display: flex;
    justify-content: center;
    align-items: center;
    /* eslint-disable-next-line vue-scoped-css/no-unused-selector */
    .dp__flex_display {
      display: block;
    }
  }
}
</style>
