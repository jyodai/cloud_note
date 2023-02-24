<template>
  <v-layout
    column
    justify-center
    align-center
  >
    <v-flex
      xs12
      sm12
      md12
    >
      <div class="image-library">
        <v-card width="300px" class="mx-auto mt-5 mb-5">
          <v-card-title>
            <v-icon size="20" />
            ファイル追加
          </v-card-title>
          <v-card-text>
            <v-file-input
              label="ファイルの選択"
              size="50"
              multiple
              @change="selectedFile"
            />
            <div>
              <div v-for="uploadFile in uploadFiles" :key="uploadFile.name">
                {{ uploadFile.name }}
              </div>
            </div>
            <v-btn class="mt-2" @click="addFile()">
              保存
            </v-btn>
            <v-btn class="mt-2" />
          </v-card-text>
        </v-card>
      </div>
    </v-flex>
  </v-layout>
</template>

<script>

export default {
  components: {
  },
  layout: 'simple',
  data () {
    return {
      uploadFiles : null,
      noteId      : null,
    }
  },
  mounted () {
    this.setGetParam()
  },
  methods: {
    async addFile () {
      const params = new FormData()
      params.append('noteId', this.noteId)
      params.append('token', this.$store.getters['User/getToken'])
      for (let i = 0; i < this.uploadFiles.length; i++) {
        params.append('file[]', this.uploadFiles[i])
      }

      const response = await this.$axios.$post(
        process.env.API_SERVER_URl + '/notes/files',
        params
      )
      alert(response.message)

      const url = process.env.SERVER_ALIAS + '/noteFile?noteId=' + this.noteId
      window.open(url, 'sub', 'top=100,left=300,width=600,height=500')
    },
    selectedFile (e) {
      this.uploadFiles = e
    },
    setGetParam () {
      const params = (new URL(document.location)).searchParams
      this.noteId = params.get('noteId')
    },
  },
}

</script>

<style>
.image-library li:hover {
  background: #666666;
}
</style>
