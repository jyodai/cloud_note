<template>
  <div class="dir-frame">
    <directory-contextmenu>
      <div id="dir-frame">
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
                <v-icon
                  v-if="node.$folded"
                  size="12"
                >
                  mdi-folder
                </v-icon>
                <v-icon
                  v-else
                  size="12"
                >
                  mdi-folder-open
                </v-icon>
              </span>

              <span v-else>
                <v-icon size="12">
                  mdi-file-outline
                </v-icon>
              </span>

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

import { Tree, Fold, Draggable, } from 'he-tree-vue';
import 'he-tree-vue/dist/he-tree-vue.css';

export default {
  components : {
    DirectoryContextmenu,
    Tree : Tree.mixPlugins([Fold, Draggable])
  },
  data () {
    return {
      treeNodes : [],
      notes     : '',
      config    : {
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
        return this.$store.getters['NoteTree/getTree'];
      },
    },
    selectTreeNoteId : {
      get () {
        return this.$store.getters['NoteTree/getSelectNoteId'];
      },
    },
  },
  watch : {
    storeTreeNodes : {
      handler () {
        const tree     = this.$store.getters['NoteTree/getDisplayTree'];
        this.treeNodes = this.$util.object.clone(tree);
      },
      deep : true,
    },
  },
  async created () {
    await this.$store.dispatch('NoteTree/loadTree');
  },
  methods : {
    setNote (note) {
      const noteTab       = this.$store.getters['NoteTab/getNoteTab'];
      const existsNoteTab = noteTab.findIndex(value => value.id === note.id);
      if (existsNoteTab === -1) {
        this.$store.dispatch('NoteTab/setNoteTab', Object.assign({}, note));
      }
      this.$store.dispatch('NoteTab/setSelectNote', note);
      this.selectNoteTree(note);
    },
    selectNoteTree (note) {
      this.$store.dispatch('NoteTree/setSelectTree', note);
    },
    async drop (event) {
      const id       = event.dragNode.data.id;
      const position = this.getPosition(event.dragNode, event.targetPath);

      await this.$store.dispatch('NoteTree/moveNode', { id, position, });
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
      await this.$store.dispatch('NoteTree/openNode', id);
    },
    async closeToggle (node) {
      const id = node.data.id;
      await this.$store.dispatch('NoteTree/closeNode', id);
    },
  },
};

</script>

<style lang="scss" scoped>
.dir-frame {
  width: 100%;
  height: 100%;
  margin-top:20px;
  padding-left : 10px;
  padding-right : 10px;
  font-size: 12px;
  #dir-frame {
    width :100%;
    height : calc(100% - 20px);
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
              background: #666666;
            }
          }
          .select-node {
            opacity: 0.6;
          }
        }
        .tree-placeholder-node{
          background : #2C7CFF;
          opacity: 0.6;
        }
    }
  }
}
</style>
