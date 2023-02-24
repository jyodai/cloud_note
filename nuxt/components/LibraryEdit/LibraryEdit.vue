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
        <div>
          <v-text-field
            v-model="newFileName"
          />
        </div>
      </template>
      <template #modalAction>
        <v-btn@click="editFile()">
          保存</v-btn>
          <v-btn@click="deleteFile()">
            削除</v-btn>
            <v-btn @click="closeModal()">
              閉じる
            </v-btn>
          </v-btn@click="deletefile()">
        </v-btn@click="editfile()">
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
      modalName   : 'LibraryEdit',
      modalOption : {
        width      : '550px',
        height     : '400px',
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
      const params = modal[0].params

      this.originFileName = params.fileName
      this.newFileName = params.fileName
    },
    closeModal () {
      this.$vfm.hide('LibraryEdit')
      this.$emit('reloadModal', 'libraryEdit')
    },
    async editFile () {
      const params = new URLSearchParams()
      params.append('token', this.$store.getters['User/getToken'])
      params.append('originFileName', this.originFileName)
      params.append('newFileName', this.newFileName)
      const config = {
        headers: {
          'X-HTTP-Method-Override' : 'PUT',
          'Content-Type'           : 'application/x-www-form-urlencoded',
        },
      }
      const response = await this.$axios.$post(process.env.API_SERVER_URl + '/libraries/files', params, config)
      alert(response.message)

      this.closeModal()
    },
    async deleteFile () {
      const params = new URLSearchParams()
      params.append('token', this.$store.getters['User/getToken'])
      params.append('originFileName', this.originFileName)
      const config = {
        headers: {
          'X-HTTP-Method-Override' : 'DELETE',
          'Content-Type'           : 'application/x-www-form-urlencoded',
        },
      }
      const response = await this.$axios.$post(process.env.API_SERVER_URl + '/libraries/files', params, config)
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
