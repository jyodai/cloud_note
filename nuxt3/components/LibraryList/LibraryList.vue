<template>
  <div class="library-list">
    <modal
      :modal-name="modalName"
      :modal-option="modalOption"
    >
      <template #modalTitle>
        ライブラリ一覧
      </template>
      <template
        #modalContent
        v-if="visible"
      >
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
                <th class="table-header-icon" />
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
                  <icon-list
                    :show-icons="['edit', 'trash']"
                    @edit="openEdit(file.fileName)"
                    @trash="deleteFile(file.fileName)"
                  />
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
import IconList from '~/commonComponents/IconList.vue'

export default {
  components: {
    Modal,
    ModalFooterButton,
    IconList,
  },
  data () {
    return {
      modalName : 'LibraryList',
      modalOption : {
        beforeOpen : this.beforeOpen,
      },
      visible : false,
      fileList  : null,
    }
  },
  methods: {
    beforeOpen () {
      const num = 0
      this.getFileList(num)
    },
    closeModal () {
      this.$vfm.hide('LibraryList')
      this.$emit('reloadModal', 'libraryList')
    },
    async getFileList (num) {
      const queryStr = '?type=list' + '&num=' + num
      const url = this.$config.public.apiUrl + '/libraries/files' + queryStr
      const response = await this.$axios.get(url)
      this.fileList = response
      this.visible = true;
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
    async deleteFile (fileName) {
      const url = this.$config.public.apiUrl + '/libraries/files';
      const params = {
        originFileName : fileName,
      };
      const config = {
        headers: {
          'X-HTTP-Method-Override' : 'DELETE',
          'Content-Type'           : 'application/x-www-form-urlencoded',
        },
      }
      const response = await this.$axios.post(url, params, config)
      alert(response.message)

      this.closeModal()
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
    .table-header-icon {
      text-align: center;
      width : 80px;
    }
  }
}
</style>
