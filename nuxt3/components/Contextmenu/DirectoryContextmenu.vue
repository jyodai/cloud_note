<template>
  <div class="directory-contextmenu">
    <div
      v-contextmenu:contextmenu
      class="contextmenu"
    >
      <slot />
    </div>
    <contextmenu ref="contextmenu">
      <contextmenu-submenu
        title="新規作成"
        class="contextmenu-sub"
      >
        <contextmenu-item @click="addNote(noteTreeStore.getSelectNoteId)">
          ノート
        </contextmenu-item>
        <contextmenu-item
          @click="addNote(noteTreeStore.getSelectNoteId, $const.NOTE_TYPE_TASK)"
        >
          タスク
        </contextmenu-item>
      </contextmenu-submenu>
      <contextmenu-item @click="editNote(noteTreeStore.getSelectNoteId)">
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
} from 'v-contextmenu';
import 'v-contextmenu/dist/themes/default.css';
import { useNoteTabStore } from '~/store/NoteTab';
import { useNoteTreeStore } from '~/store/NoteTree';

export default {
  directives : {
    contextmenu : directive,
  },
  components : {
    Contextmenu,
    ContextmenuItem,
    ContextmenuSubmenu,
  },
  data () {
    return {
      noteTabStore  : useNoteTabStore(),
      noteTreeStore : useNoteTreeStore(),
      note          : null,
      config        : {
        headers : {
          'Content-Type' : 'application/x-www-form-urlencoded',
        },
      },
    };
  },
  methods : {
    async getNote () {
      const noteId   = this.noteTreeStore.getSelectNoteId;
      const url      = this.$config.public.apiUrl + `/notes/${noteId}`;
      const response = await this.$axios.get(url);
      return response.data;
    },
    async addNote (noteId = null, noteType = this.$const.NOTE_TYPE_NORMAL) {
      if (noteId === null) {
        alert('ノートが選択されていません');
        return;
      }

      const noteTitle = window.prompt('ノートのタイトルを入力してください。');

      const data = {
        noteId,
        noteType,
        noteTitle,
      };
      await this.noteTreeStore.addNode({ data });
    },
    async editNote (noteId = null) {
      const noteTitle = window.prompt('ノートのタイトルを入力してください。');

      const data = {
        noteId,
        noteTitle,
      };
      await this.noteTreeStore.updateNode({ data });
      if (this.noteTabStore.findNote(data.noteId)) {
        await this.noteTabStore.updateNote(data);
      }
    },
    // Todo:編集 Modalを開いてそこでプロパティも一緒に出す
    async deleteNote () {
      if (!confirm('ファイルを削除します')) {
        return;
      }
      const deleteInfo = await this.noteTreeStore.deleteNode();
      this.deleteNoteTab(deleteInfo);
      this.deleteNoteContent(deleteInfo);
    },
    deleteNoteTab (deleteInfo) {
      deleteInfo.delete_note_id.forEach(
        (noteId) => {
          this.noteTabStore.removeNoteTab(noteId);
        }
      );
    },
    deleteNoteContent (deleteInfo) {
      const selectNoteId = this.noteTabStore.getSelectNoteId;
      if (deleteInfo.delete_note_id.includes(selectNoteId)) {
        this.noteTabStore.unsetSelectNote();
      }
    },
    async property () {
      const note = await this.getNote();
      const str  = `タイトル : ${note.title}
ID : ${note.id}
ParentId : ${note.parent_note_id}
表示順 : ${note.display_num}
階層 : ${note.hierarchy}
`;
      window.alert(str);
    },
  },
};

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

<!-- eslint-disable-next-line vue-scoped-css/enforce-style-type -->
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
