<template>
  <div class="library-list">
    <modal
      :modal-name="modalName"
    >
      <template #modalTitle>
        ライブラリ一覧
      </template>
      <template #modalContent>
        <div class="content-header">
          <v-btn class="mb-2" @click="openAddImageLibrary()">
            追加
          </v-btn>
        </div>

        <div class="content-body g-table-list">
          <table>
            <thead>
              <tr>
                <th>
                  ファイル名
                </th>
                <th />
              </tr>
            </thead>
            <tbody>
              <tr v-for="file in fileList" :key="file.fileName">
                <td
                  @click="getFileHtml(file.fileHtml)"
                >
                  {{ file.fileName }}
                </td>
                <td>
                  <span @click="openEdit(file.fileName)">編集</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </template>
      <template #modalAction>
        <modal-footer-button
          :visible-lists="['close']"
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
      modalName : 'LibraryList',
      fileList  : null,
    }
  },
  created () {
    const num = 0
    this.getFileList(num)
  },
  mounted () {},
  methods: {
    closeModal () {
      this.$vfm.hide('LibraryList')
      this.$emit('reloadModal', 'libraryList')
    },
    async getFileList (num) {
      const queryStr = '?token=' + this.$store.getters['User/getToken'] + '&type=list' + '&num=' + num
      const response = await this.$axios.get(this.$config.public.apiUrl + '/libraries/files' + queryStr)
      this.fileList = response
    },
    getFileHtml (str) {
      const copyFrom = document.createElement('textarea')
      copyFrom.textContent = str

      const bodyElm = document.getElementsByTagName('body')[0]
      bodyElm.appendChild(copyFrom)

      copyFrom.select()
      document.execCommand('copy')

      bodyElm.removeChild(copyFrom)
      alert('HTMLをコピーしました')
    },
    openAddImageLibrary () {
      this.$vfm.show('LibraryAdd')
    },
    openEdit (fileName) {
      const modal = this.$vfm.get('LibraryEdit')
      modal[0].params = { fileName, }
      this.$vfm.show('LibraryEdit')
    },
  },
}
</script>

<style lang="scss" scoped>
.library-list {
  height: 100%;
  width: 100%;

  .content-header {
    height: 50px;
    text-align: right;
  }

  .content-body {
    height: calc(100% - 50px);
    overflow: auto;
  }
}
</style>
