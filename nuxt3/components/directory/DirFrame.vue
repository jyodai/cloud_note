<template>
  <div
    class="dir-frame"
    @click.right.prevent
  >
    <div class="head-area">
      <div class="left">
        ページ一覧
      </div>
      <div class="right">
        <v-icon
          class="g-pointer"
          size="16"
          @click="addNote()"
        >
          mdi-plus
        </v-icon>
      </div>
    </div>

    <div
      id="dir-frame"
      class="tree-area"
    >
      <directory-contextmenu>
        <Tree
          ref="tree"
          :value="treeNodes"
          @drop="drop"
          @node-folded-changed="nodeFoldedChanged"
          @click="clearSelectedNoteTree()"
          @click.right="clearSelectedNoteTree()"
        >
          <template #default="{node, path, tree}">
            <div
              class="tree-node-container"
              :class="{'select-node': selectTreeNoteId === node.data.id}"
              @click="
                setNote(node.data);
                selectNoteTree(node.data)
              "
              @click.right="selectNoteTree(node.data)"
            >
              <span
                v-if="node.data.hasChild"
                @click.stop="tree.toggleFold(node, path);"
              >
                <template v-if="node.$folded">
                  <v-icon
                    size="14"
                  >
                    mdi-chevron-right
                  </v-icon>
                  <v-icon
                    size="14"
                  >
                    mdi-folder
                  </v-icon>
                </template>
                <template v-else>
                  <v-icon
                    size="14"
                  >
                    mdi-chevron-down
                  </v-icon>
                  <v-icon
                    size="14"
                  >
                    mdi-folder-open
                  </v-icon>
                </template>
              </span>

              <span v-else>
                <v-icon size="14" />
                <v-icon size="14">
                  mdi-file-outline
                </v-icon>
              </span>

              <span class="mr-1" />
              <span>
                {{ node.title }}
              </span>
            </div>
          </template>
        </Tree>
      </directory-contextmenu>
    </div>
  </div>
</template>

<script>
import DirectoryContextmenu from '../Contextmenu/DirectoryContextmenu.vue';
import { useNoteTabStore } from '~/store/NoteTab';
import { useNoteTreeStore } from '~/store/NoteTree';

import { Tree, Fold, Draggable, } from 'he-tree-vue';
import 'he-tree-vue/dist/he-tree-vue.css';

export default {
  components : {
    DirectoryContextmenu,
    Tree : Tree.mixPlugins([Fold, Draggable])
  },
  data () {
    return {
      noteTabStore  : useNoteTabStore(),
      noteTreeStore : useNoteTreeStore(),
      treeNodes     : [],
      notes         : '',
      config        : {
        headers : {
          'Content-Type' : 'application/x-www-form-urlencoded',
        },
      },
      dragNoteId        : null,
      skipTreeSelection : false,
    };
  },
  computed : {
    storeTreeNodes : {
      get () {
        return this.noteTreeStore.getTree;
      },
    },
    selectTreeNoteId : {
      get () {
        return this.noteTreeStore.getSelectNoteId;
      },
    },
  },
  watch : {
    storeTreeNodes : {
      handler () {
        const tree     = this.noteTreeStore.getDisplayTree;
        this.treeNodes = this.$util.object.clone(tree);
      },
      deep : true,
    },
  },
  async created () {
    await this.noteTreeStore.loadTree();
  },
  methods : {
    setNote (note) {
      this.noteTabStore.setNoteTab(Object.assign({}, note));
      this.noteTabStore.setSelectNote(note);
    },
    selectNoteTree (note) {
      this.noteTreeStore.setSelectTree(note);
      this.skipTreeSelection = true;
    },
    clearSelectedNoteTree() {
      if (this.skipTreeSelection) {
        this.skipTreeSelection = false;
        return;
      }
      this.noteTreeStore.setSelectTree(null);
    },
    async drop (event) {
      const id       = event.dragNode.data.id;
      const position = this.getPosition(event.dragNode, event.targetPath);

      await this.noteTreeStore.moveNode({ id, position });
    },
    getPosition(node, path) {
      const tree       = this.$refs.tree;
      const position   = {};
      const parentNode = tree.getNodeParentByPath(path);
      if (!parentNode || parentNode.data.hasChild) {
        const lastPath = path.pop();
        if (lastPath === 0) {
          path.push(lastPath + 1);
          position.placement = 'before';
        } else {
          path.push(lastPath - 1);
          position.placement = 'after';
        }
        position.node = tree.getNodeByPath(path);
      } else {
        position.node      = parentNode;
        position.placement = 'inside';
      }
      return position;
    },
    nodeFoldedChanged(node) {
      this.toggle(node);
    },
    toggle (node) {
      if (node.$folded) {
        this.closeToggle(node);
      } else {
        this.openToggle(node);
      }
    },
    async openToggle (node) {
      const id = node.data.id;
      await this.noteTreeStore.openNode(id);
    },
    async closeToggle (node) {
      const id = node.data.id;
      await this.noteTreeStore.closeNode(id);
    },
    async addNote () {
      const data = {
        noteId    : 0,
        noteType  : this.$const.NOTE_TYPE_NORMAL,
        noteTitle : '無題',
      };
      await this.noteTreeStore.addNode({ data });
    }
  },
};

</script>

<style lang="scss" scoped>
.dir-frame {
  width: 100%;
  height: 100%;
  width: 100%;
  padding-left: 10px;
  font-size: 14px;
  overflow : auto;

  .head-area {
    padding-top : 10px;
    padding-right : 5px;
    display: flex;
    .right {
      margin-left: auto;
      &:hover {
        background: $color-hover;
      }
    }
  }

  .tree-area {
    padding-top : 5px;
  }
}
</style>

<!-- eslint-disable-next-line vue-scoped-css/enforce-style-type -->
<style lang="scss">
.he-tree {
  background-color: inherit;
  border : 0px;
  .tree-children {
    overflow : initial;
    .tree-branch {
        white-space: nowrap;
        .tree-node {
          border:none;
          padding:0;
          .tree-node-container {
            cursor: pointer;
            &:hover {
              background: $color-hover;
            }
          }
          .select-node {
            color: $color-text-primary;
            background: $color-select;
          }
        }
    }
  }
}
</style>
