<template>
  <div class="library-edit">
    <modal
      :modal-name="modalName"
      :modal-option="modalOption"
    >
      <template #modalTitle>
        ライブラリ編集
      </template>
      <template #modalContent>
        <div class="g-table-edit">
          <table>
            <tbody>
              <tr>
                <th>
                  ファイル名
                </th>
                <td>
                  <input
                    v-model="newFileName"
                    type="text"
                  >
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </template>
      <template #modalAction>
        <modal-footer-button
          :visible-lists="['save', 'close']"
          @save="editFile()"
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
      modalName   : 'LibraryEdit',
      modalOption : {
        beforeOpen : this.beforeOpen,
      },
      uploadFiles : null,
      newFileName : null,
    };
  },
  methods : {
    beforeOpen () {
      this.loadParams();
    },
    loadParams () {
      const params = this.$vfm.getParams('LibraryEdit');

      this.originFileName = params.fileName;
      this.newFileName    = params.fileName;
    },
    closeModal (closeType = this.$const.MODAL_CLOSE_TYPE_CLOSE) {
      this.$vfm.close('LibraryEdit', closeType);
    },
    async editFile () {
      const url    = this.$config.public.apiUrl + '/libraries';
      const params = {
        originFileName : this.originFileName,
        newFileName    : this.newFileName,
      };
      await this.$axios.put(url, params)
        .then(() => {
          this.closeModal(this.$const.MODAL_CLOSE_TYPE_SAVE);
        })
        .catch(() => { return; })
      ;
    },
  },
};
</script>

<style lang="scss" scoped>
.library-edit {
  height: 100%;
  width: 100%;
}
</style>
