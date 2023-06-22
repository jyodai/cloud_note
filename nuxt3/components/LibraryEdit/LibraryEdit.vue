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
                  <input type="text" v-model="newFileName">
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
import Modal from '../Modal/ModalWrapper.vue'
import ModalFooterButton from '~/commonComponents/ModalFooterButton.vue'

export default {
  components: {
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
    }
  },
  created () {},
  mounted () {},
  methods: {
    beforeOpen () {
      this.loadParams()
    },
    loadParams () {
      const modal = this.$vfm.get('LibraryEdit')
      const params = modal.params

      this.originFileName = params.fileName
      this.newFileName = params.fileName
    },
    closeModal () {
      this.$vfm.close('LibraryEdit')
    },
    async editFile () {
      const url = this.$config.public.apiUrl + '/libraries/files';
      const params = {
        originFileName : this.originFileName,
        newFileName    : this.newFileName,
      };
      const response = await this.$axios.put(url, params)
      alert(response.message)

      this.closeModal()
    },
    setGetParam () {
    },
  },
}
</script>

<style lang="scss" scoped>
.library-edit {
  height: 100%;
  width: 100%;
}
</style>
