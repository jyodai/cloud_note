<template>
  <div class="dir-frame">
    <directory-contextmenu>
      <div id="dir-frame" onContextmenu="return false;">
        <!-- <vue-power-tree -->
        <!--   v-model="treeNodes" -->
        <!--   @toggle="toggle($event)" -->
        <!--   @drop="drop" -->
        <!-- > -->
        <!--   <template slot="title" slot-scope="{ node }"> -->
        <!--     <span -->
        <!--       @click="setNote(node.data)" -->
        <!--       @mouseup.right="selectNoteTree(node.data)" -->
        <!--     > -->
        <!--       {{ node.title }} -->
        <!--     </span> -->
        <!--   </template> -->
        <!--  -->
        <!--   <template slot="toggle" slot-scope="{ node }"> -->
        <!--     <span v-if="node.data.hasChild"> -->
        <!--       <v-icon v-if="node.isExpanded" size="12"> -->
        <!--         mdi-folder-open -->
        <!--       </v-icon> -->
        <!--       <v-icon v-else size="12"> -->
        <!--         mdi-folder -->
        <!--       </v-icon> -->
        <!--     </span> -->
        <!--     <span v-else> -->
        <!--       <v-icon size="12"> -->
        <!--         mdi-file-outline -->
        <!--       </v-icon> -->
        <!--     </span> -->
        <!--   </template> -->
        <!-- </vue-power-tree> -->
        <!-- <vue-power-tree v-model="nodes"/> -->
        <Tree :value="treeNodes" @drop="drop">
          <template v-slot="{node, index, path, tree}">
            <span
              v-if="node.data.hasChild"
              @click="toggle(node)"
            >
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

            <span
              @click="setNote(node.data)"
              @mouseup.right="selectNoteTree(node.data)"
            >
              {{ node.title }}
            </span>
          </template>
        </Tree>
      </div>
    </directory-contextmenu>
  </div>
</template>

<script>
import DirectoryContextmenu from '../Contextmenu/DirectoryContextmenu.vue'

import {
  Tree, // Base tree
  Fold, Draggable, // plugins
  foldAll, unfoldAll, cloneTreeData, walkTreeData, getPureTreeData, // utils
} from 'he-tree-vue'
import 'he-tree-vue/dist/he-tree-vue.css' // base style

export default {
  components: {
    DirectoryContextmenu,
    Tree: Tree.mixPlugins([Fold, Draggable])
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
        this.$util.load.createLoadScreen(targetElementId)
      } else {
        this.$util.load.deleteLoadScreen()
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
      this.$store.dispatch('NoteTab/setSelectNote', note)
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
    toggle (node) {
      if (node.isExpanded) {
        this.closeToggle(node)
      } else {
        this.openToggle(node)
      }
    },
    async openToggle (node) {
      const id = node.data.id
      this.noteLoadFlag = true
      await this.$store.dispatch('NoteTree/openNode', id)
        .finally(() => { this.noteLoadFlag = false })
    },
    async closeToggle (node) {
      const id = node.data.id
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
