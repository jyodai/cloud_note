<template>
  <div class="library-add">
    <modal
      :modal-name="modalName"
      :modal-option="{ width : '550px', height : '400px'}"
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
              @change="selectedFile"
            />
          </div>
          <div v-for="uploadFile in uploadFiles" :key="uploadFile.name">
            {{ uploadFile.name }}
          </div>
        </div>
      </template>
      <template #modalAction>
        <v-btn class="mb-2" @click="addFile()">
          保存
        </v-btn>
        <v-btn class="mb-2" @click="closeModal()">
          閉じる
        </v-btn>
      </template>
    </modal>
  </div>
</template>

<script>
import Modal from '../Modal/Modal.vue'

export default {
  components: {
    Modal,
  },
  data () {
    return {
      modalName   : 'LibraryAdd',
      uploadFiles : null,
    }
  },
  created () {},
  mounted () {},
  methods: {
    closeModal () {
      this.$vfm.hide('LibraryAdd')
      this.$emit('reloadModal', 'libraryAdd')
    },
    async addFile () {
      const params = new FormData()
      params.append('token', this.$store.getters['User/getToken'])
      for (let i = 0; i < this.uploadFiles.length; i++) {
        params.append('file[]', this.uploadFiles[i])
      }

      const response = await this.$axios.$post(
        process.env.API_SERVER_URl + '/libraries/files',
        params
      )
      alert(response.message)

      this.closeModal()
    },
    selectedFile (e) {
      this.uploadFiles = e
    },
  },
}
</script>

<style lang="scss" scoped>
.library-add {
  height: 100%;
  width: 100%;
}
</style>
