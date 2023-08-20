<template>
  <div class="dir-frame">
    <directory-contextmenu>
      <div id="dir-frame" class="tree-area">
        <Tree
          ref="tree"
          :value="treeNodes"
          @drop="drop"
          @node-folded-changed="nodeFoldedChanged"
          @click="selectNoteTree(null)"
          @mouseup.right="selectNoteTree(null)"
        >
          <template #default="{node, path, tree}">
            <div
              class="tree-node-container"
              :class="{'select-node': selectTreeNoteId === node.data.id}"
              @click.stop="setNote(node.data)"
              @mouseup.right.stop="selectNoteTree(node.data)"
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
      </div>
    </directory-contextmenu>
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
      dragNoteId : null,
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
      this.selectNoteTree(note);
    },
    selectNoteTree (note) {
      this.noteTreeStore.setSelectTree(note);
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
  .tree-area {
    width :100%;
    height : 100%;
  }
}
</style>

<!-- eslint-disable-next-line vue-scoped-css/enforce-style-type -->
<style lang="scss">
.he-tree {
  width: 100%;
  height: 100%;
  background-color: inherit;
  border : 0px;
  overflow : auto;
  padding-top : 10px;
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
