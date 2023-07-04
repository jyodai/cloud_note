<template>
  <div class="library-add">
    <modal
      :modal-name="modalName"
      :modal-option="modalOption"
    >
      <template #modalTitle>
        ライブラリ追加
      </template>
      <template #modalContent>
        <div>
          <div>
            <v-file-input
              accept="image/png,image/jpeg"
              label="画像ファイルの選択"
              multiple
              :clearable="false"
              variant="underlined"
              @change="selectedFile"
            />
          </div>
          <div
            v-for="uploadFile in uploadFiles"
            :key="uploadFile.name"
          >
            {{ uploadFile.name }}
          </div>
        </div>
      </template>
      <template #modalAction>
        <modal-footer-button
          :visible-lists="['save', 'close']"
          @save="addFile()"
          @close="closeModal()"
        />
      </template>
    </modal>
  </div>
</template>

<script>
import Modal from '../Modal/ModalWrapper.vue';
import ModalFooterButton from '~/commonComponents/ModalFooterButton.vue';

export default {
  components : {
    Modal,
    ModalFooterButton,
  },
  data () {
    return {
      modalName   : 'LibraryAdd',
      modalOption : {
        beforeOpen : this.beforeOpen,
        width      : '550px',
        height     : '400px',
      },
      uploadFiles : null,
    };
  },
  methods : {
    beforeOpen () {
      this.load();
    },
    load () {
      this.uploadFiles = null;
    },
    closeModal (closeType = this.$const.MODAL_CLOSE_TYPE_CLOSE) {
      this.$vfm.close('LibraryAdd', closeType);
    },
    async addFile () {
      if (!this.uploadFiles) {
        alert('ファイルを選択してください。');
        return;
      }

      const url    = this.$config.public.apiUrl + '/libraries';
      const params = new FormData();
      for (let i = 0; i < this.uploadFiles.length; i++) {
        params.append('file[]', this.uploadFiles[i]);
      }

      await this.$axios.post(url, params)
        .then(() => {
          this.closeModal(this.$const.MODAL_CLOSE_TYPE_SAVE);
        })
        .catch(() => { return; })
      ;
    },
    selectedFile (event) {
      this.uploadFiles = event.target.files;
    },
  },
};
</script>

<style lang="scss" scoped>
.library-add {
  height: 100%;
  width: 100%;
}
</style>
