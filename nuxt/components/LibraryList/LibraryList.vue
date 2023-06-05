<template>
  <div class="library-list">
    <modal
      :modal-name="modalName"
    >
      <template #modalTitle>
        ライブラリ一覧
      </template>
      <template #modalContent>
        <div>
          ファイル一覧
        </div>
        <div>
          <v-btn class="mb-2" @click="openAddImageLibrary()">
            ファイルの追加
          </v-btn>
          <ul v-for="file in fileList" :key="file.fileName" onContextmenu="return false;">
            <li
              @click="getFileHtml(file.fileHtml)"
              @mouseup.right="openEdit(file.fileName)"
            >
              {{ file.fileName }}
            </li>
          </ul>
        </div>
      </template>
      <template #modalAction>
        <v-btn class="mb-2" @click="closeModal()">
          閉じる
        </v-btn>
      </template>
    </modal>
  </div>
</template>

<script>
import Modal from '../Modal/ModalWrapper.vue'

export default {
  components: {
    Modal,
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
      const response = await this.$axios.$get(process.env.API_SERVER_URl + '/libraries/files' + queryStr)
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
}
</style>
