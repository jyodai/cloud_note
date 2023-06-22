<template>
  <div class="directory-contextmenu">
    <div v-contextmenu:contextmenu class="contextmenu">
      <slot />
    </div>
    <contextmenu ref="contextmenu">
      <contextmenu-submenu title="新規作成" class="contextmenu-sub">
        <contextmenu-item @click="addNote(0)">
          ルートノート
        </contextmenu-item>
        <contextmenu-item @click="addNote($store.getters['NoteTree/getSelectNoteId'])">
          ノート
        </contextmenu-item>
        <contextmenu-item
          @click="addNote($store.getters['NoteTree/getSelectNoteId'], $const.NOTE_TYPE_TASK)"
        >
          タスク
        </contextmenu-item>
      </contextmenu-submenu>
      <contextmenu-item @click="editNote($store.getters['NoteTree/getSelectNoteId'])">
        編集
      </contextmenu-item>
      <contextmenu-item @click="deleteNote()">
        削除
      </contextmenu-item>
      <contextmenu-item @click="property()">
        プロパティ
      </contextmenu-item>
    </contextmenu>
  </div>
</template>

<script>
import {
  directive,
  Contextmenu,
  ContextmenuItem,
  ContextmenuSubmenu
} from 'v-contextmenu'
import 'v-contextmenu/dist/themes/default.css';

export default {
  directives: {
    contextmenu: directive,
  },
  components: {
    Contextmenu,
    ContextmenuItem,
    ContextmenuSubmenu,
  },
  data () {
    return {
      note   : null,
      config : {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
      },
    }
  },
  watch: {
  },
  mounted () {
  },
  methods: {
    async getNote () {
      const noteId = this.$store.getters['NoteTree/getSelectNoteId']
      const url = this.$config.public.apiUrl + '/notes' + '?noteId=' + noteId
      return await this.$axios.get(url)
    },
    async addNote (noteId = null, noteType = this.$const.NOTE_TYPE_NORMAL) {
      if (noteId === null) {
        alert('ノートが選択されていません');
        return;
      }

      const noteTitle = window.prompt('ノートのタイトルを入力してください。')
      if (!noteTitle) {
        alert('ノートのタイトルが空です')
        return
      }

      const data = {
        noteId,
        noteType,
        noteTitle,
      }
      await this.$store.dispatch('NoteTree/addNode', { data, })
    },
    async editNote (noteId = null) {
      const noteTitle = window.prompt('ノートのタイトルを入力してください。')
      if (!noteTitle) {
        alert('ノートのタイトルが空です')
        return
      }

      const data = {
        noteId,
        noteTitle,
      }
      await this.$store.dispatch('NoteTree/updateNode', { data, })
      if (this.$store.getters['NoteTab/findNote'](data.noteId)) {
        await this.$store.dispatch('NoteTab/updateNote', { data, })
      }
    },
    // Todo:編集 Modalを開いてそこでプロパティも一緒に出す
    async deleteNote () {
      if (!confirm('ファイルを削除します')) {
        return
      }
      const deleteInfo = await this.$store.dispatch('NoteTree/deleteNode')
      this.deleteNoteTab(deleteInfo)
      this.deleteNoteContent(deleteInfo)
    },
    deleteNoteTab (deleteInfo) {
      deleteInfo.deleteNoteId.forEach(
        (noteId) => {
          this.$store.dispatch('NoteTab/removeNoteTab', noteId)
        }
      )
    },
    deleteNoteContent (deleteInfo) {
      const selectNoteId = this.$store.getters['NoteTab/getSelectNoteId']
      if (deleteInfo.deleteNoteId.includes(selectNoteId)) {
        this.$store.dispatch('NoteTab/unsetSelectNote')
      }
    },
    async property () {
      const note = await this.getNote()
      const str = `タイトル : ${note.title}
ID : ${note.id}
ParentId : ${note.parent_note_id}
表示順 : ${note.display_num}
階層 : ${note.hierarchy}
`
      window.alert(str)
    },
  },
}

</script>

<style lang="scss" scoped>
.directory-contextmenu {
  width: 100%;
  height: 100%;
  .contextmenu {
    width: 100%;
    height: 100%;
  }
}
</style>

<style>
/* Todo:グローバルなスタイルはどこかにまとめる */
.v-contextmenu {
  font-size: 12px;
  padding: 3px 5px;
}
.v-contextmenu .v-contextmenu-item {
  padding: 3px 5px;
}
</style>
