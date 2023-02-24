<template>
  <div class="dir-frame">
    <directory-contextmenu>
      <div id="dir-frame" onContextmenu="return false;">
        <sl-vue-tree
          v-model="treeNodes"
          @toggle="toggle($event)"
          @drop="drop"
        >
          <template slot="title" slot-scope="{ node }">
            <span
              @click="setNote(node.data)"
              @mouseup.right="selectNoteTree(node.data)"
            >
              {{ node.title }}
            </span>
          </template>

          <template slot="toggle" slot-scope="{ node }">
            <span v-if="node.data.hasChild">
              <v-icon v-if="node.isExpanded" size="12">
                mdi-folder-open
              </v-icon>
              <v-icon v-else size="12">
                mdi-folder
              </v-icon>
            </span>
            <span v-else>
              <v-icon size="12">
                mdi-file-outline
              </v-icon>
            </span>
          </template>
        </sl-vue-tree>
      </div>
    </directory-contextmenu>
  </div>
</template>

<script>
import SlVueTree from 'sl-vue-tree'
import 'sl-vue-tree/dist/sl-vue-tree-dark.css'
import DirectoryContextmenu from '../Contextmenu/DirectoryContextmenu.vue'

export default {
  components: {
    DirectoryContextmenu,
    SlVueTree,
  },
  data () {
    return {
      treeNodes : [],
      notes     : '',
      config    : {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
      },
      noteLoadFlag : false,
      dragNoteId   : null,
    }
  },
  computed: {
    storeTreeNodes: {
      get () {
        return this.$store.getters['NoteTree/getTree']
      },
    },
  },
  watch: {
    noteLoadFlag (newVal, oldVal) {
      if (newVal) {
        const targetElementId = 'dir-frame'
        this.createLoadScreen(targetElementId)
      } else {
        this.deleteLoadScreen()
      }
    },
    storeTreeNodes: {
      handler () {
        this.treeNodes = this.$store.getters['NoteTree/getDisplayTree']
      },
      deep: true,
    },
  },
  async created () {
    this.noteLoadFlag = true
    await this.$store.dispatch('NoteTree/loadTree')
      .finally(() => { this.noteLoadFlag = false })
  },
  methods: {
    setNote (note) {
      const noteTab = this.$store.getters['NoteTab/getNoteTab']
      const existsNoteTab = noteTab.findIndex(value => value.id === note.id)
      if (existsNoteTab === -1) {
        this.$store.dispatch('NoteTab/setNoteTab', Object.assign({}, note))
      }
      this.$store.dispatch('NoteContent/loadSelectNote', { noteId: note.id, })
    },
    selectNoteTree (note) {
      this.$store.dispatch('NoteTree/setSelectTree', note)
    },
    async drop (nodes, position, event) {
      this.noteLoadFlag = true
      await this.$store.dispatch('NoteTree/moveNode', { nodes, position, })
        .finally(() => {
          this.noteLoadFlag = false
        })
    },
    toggle (event) {
      if (event.isExpanded) {
        this.closeToggle(event)
      } else {
        this.openToggle(event)
      }
    },
    async openToggle (event) {
      const id = event.data.id
      this.noteLoadFlag = true
      await this.$store.dispatch('NoteTree/openNode', id)
        .finally(() => { this.noteLoadFlag = false })
    },
    async closeToggle (event) {
      const id = event.data.id
      this.noteLoadFlag = true
      await this.$store.dispatch('NoteTree/closeNode', id)
        .finally(() => { this.noteLoadFlag = false })
    },
  },
}

</script>

<style lang="scss" scoped>
.dir-frame {
  width: 100%;
  height: 100%;
  margin-top:20px;
  padding-left : 5px;
  padding-right : 5px;
  font-size: 12px;
  #dir-frame {
    width :100%;
    height : calc(100% - 20px);
  }
}
</style>

<style lang="scss">
.sl-vue-tree.sl-vue-tree-root {
  width: 100%;
  height: 100%;
  background-color: inherit;
  border : 0px;
  overflow : auto;
  .sl-vue-tree-nodes-list {
    overflow : initial;
    .sl-vue-tree-selected {
    }
    .sl-vue-tree-node {
      .sl-vue-tree-node-is-leaf {
      }
      .sl-vue-tree-node-item {
        background-color: inherit;
        line-height : 18px;
        white-space: nowrap;
        .sl-vue-tree-gap {
          min-width : 25px;
        }
        .sl-vue-tree-title {
          .sl-vue-tree-toggle {
            width : 12px;
          }
        }
      }
    }
  }
}
</style>
