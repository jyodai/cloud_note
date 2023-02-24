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
            ファイル編集
          </v-card-title>
          <v-card-text>
            <v-text-field
              v-model="newFileName"
            />
            <v-btn @click="editFile()">
              保存
            </v-btn>
            <v-btn @click="deleteFile()">
              削除
            </v-btn>
            <v-btn @click="deletefile()" />
            <v-btn @click="editfile()" />
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
      originFileName : null,
      newFileName    : null,
      noteId         : null,
      config         : {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
      },
    }
  },
  mounted () {
    this.setGetParam()
  },
  methods: {
    async editFile () {
      const params = new URLSearchParams()
      params.append('token', this.$store.getters['User/getToken'])
      params.append('originFileName', this.originFileName)
      params.append('newFileName', this.newFileName)
      params.append('noteId', this.noteId)
      const config = {
        headers: {
          'X-HTTP-Method-Override' : 'PUT',
          'Content-Type'           : 'application/x-www-form-urlencoded',
        },
      }
      const response = await this.$axios.$post(process.env.API_SERVER_URl + '/notes/files', params, config)
      alert(response.message)
      const url = process.env.SERVER_ALIAS + '/noteFile?noteId=' + this.noteId
      window.open(url, 'sub', 'top=100,left=300,width=600,height=500')
    },
    async deleteFile () {
      const params = new URLSearchParams()
      params.append('token', this.$store.getters['User/getToken'])
      params.append('originFileName', this.originFileName)
      params.append('noteId', this.noteId)
      const config = {
        headers: {
          'X-HTTP-Method-Override' : 'DELETE',
          'Content-Type'           : 'application/x-www-form-urlencoded',
        },
      }
      const response = await this.$axios.$post(process.env.API_SERVER_URl + '/notes/files', params, config)
      alert(response.message)
      const url = process.env.SERVER_ALIAS + '/noteFile?noteId=' + this.noteId
      window.open(url, 'sub', 'top=100,left=300,width=600,height=500')
    },
    setGetParam () {
      const params = (new URL(document.location)).searchParams
      this.originFileName = params.get('fileName')
      this.newFileName = params.get('fileName')
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
