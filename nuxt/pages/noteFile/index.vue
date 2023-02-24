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
            ファイル一覧
          </v-card-title>
          <v-card-text>
            <v-btn class="mb-2" @click="openAddFileToFile()">
              ファイルの追加
            </v-btn>
            <ul v-for="file in fileList" :key="file.fileName" onContextmenu="return false;">
              <li
                @click="download(file.fileName)"
                @mouseup.right="openEdit(file.fileName)"
              >
                {{ file.fileName }}</a>
              </li>
            </ul>
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
      fileList : '',
      noteId   : null,
    }
  },
  mounted () {
    this.setGetParam()
    const num = 0
    this.getFileList(num)
  },
  methods: {
    async getFileList (num) {
      const queryStr = '?token=' + this.$store.getters['User/getToken'] + '&type=list' + '&noteId=' + this.noteId
      const response = await this.$axios.$get(process.env.API_SERVER_URl + '/notes/files' + queryStr)
      this.fileList = response
    },
    download (fileName) {
      const queryStr = '?token=' + this.$store.getters['User/getToken'] + '&type=download' + '&noteId=' + this.noteId + '&fileName=' + fileName
      const url = process.env.API_SERVER_URl + '/notes/files' + queryStr
      const a = document.createElement('a')
      a.download = fileName
      a.href = url
      a.click()
    },
    openAddFileToFile () {
      const url = process.env.SERVER_ALIAS + '/noteFile/add?noteId=' + this.noteId
      window.open(url, 'sub', 'top=100,left=300,width=600,height=500')
    },
    openEdit (fileName) {
      const url = process.env.SERVER_ALIAS + '/noteFile/edit?fileName=' + fileName + '&noteId=' + this.noteId
      window.open(url, 'sub', 'top=100,left=300,width=600,height=500')
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
