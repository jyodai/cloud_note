import { defineStore } from 'pinia';

const nuxtApp = useNuxtApp();

export const useNoteTreeStore = defineStore({
  id    : 'noteTree',
  state : () => ({
    selectNote : null,
    treeNodes  : [],
  }),
  getters : {
    getSelectNoteId : state => state.selectNote === null ? null : state.selectNote.id,
    getTree         : state => state.treeNodes,
    getDisplayTree  : state => state.treeNodes[0] ? state.treeNodes[0].children : [],
    findTreeNode    : function () { 
      return function (treeNodes, id) {
        let ret = null;
        treeNodes.forEach((node) => {
          if (ret !== null) {
            return;
          }
          if (node.data.id === id) {
            ret = node;
            return;
          }
          if (node.children.length !== 0) {
            ret = this.findTreeNode(node.children, id);
          }
        });
        return ret;
      };
    }
  },
  actions : {
    setSelectTree (note) {
      this.selectNote = note;
    },
    async loadTree () {
      const restoreTreeLists = nuxtApp.$util.localStorage.exists('restoreTreeLists')
        ? nuxtApp.$util.localStorage.get('restoreTreeLists')
        : [];

      const url      = nuxtApp.$config.public.apiUrl + '/tree';
      const params   = {
        tree : JSON.stringify(restoreTreeLists),
      };
      const config   = { params, };
      const response = await nuxtApp.$axios.get(url, config);

      const rootNode = [
        {
          children : convertTree(response),
          data     : {
            id       : 0,
            hasChild : true,
          },
        },
      ];
      this.treeNodes = rootNode;
    },
    async loadChildrenTree (id) {
      const url            = nuxtApp.$config.public.apiUrl + '/tree' + '/' + id + '/' + 'children';
      nuxtApp.noteLoadFlag = true;
      const response       = await nuxtApp.$axios.get(url);
      const addTreeNodes   = convertTree(response);

      const parentTreeNode    = this.findTreeNode(this.getTree, id);
      parentTreeNode.children = addTreeNodes;
    },
    async openNode (id) {
      const node   = this.findTreeNode(this.getTree, id);
      const flag   = false;
      node.$folded = flag;

      await this.loadChildrenTree(id);

      await this.addRestoreTreeData(id);
    },
    async closeNode (id) {
      const node   = this.findTreeNode(this.getTree, id);
      const flag   = true;
      node.$folded = flag;

      const parentTreeNode    = this.findTreeNode(this.getTree, id);
      parentTreeNode.children = [];

      await this.removeRestoreTreeData(id);
    },
    addRestoreTreeData (id) {
      let restore = nuxtApp.$util.localStorage.get('restoreTreeLists');
      if (restore === null) {
        restore = [];
      }

      if (!restore.includes(id)) {
        restore.push(id);
      }

      nuxtApp.$util.localStorage.set('restoreTreeLists', restore);
    },
    removeRestoreTreeData (id) {
      const restoreLists = nuxtApp.$util.localStorage.get('restoreTreeLists');
      const index        = restoreLists.indexOf(id);
      if (index === -1) {
        return;
      }
      restoreLists.splice(index, 1);

      // ネスト内の子ファイルが開いていた場合の対策
      const newRestoreLists = [];
      restoreLists.forEach((treeId) => {
        if (this.findTreeNode(this.getTree, treeId) !== null) {
          newRestoreLists.push(treeId);
        }
      });

      nuxtApp.$util.localStorage.set('restoreTreeLists', newRestoreLists);
    },
    async moveNode ({ id, position }) {
      const targetId = position.node.data.id;
      const type     = position.placement;
      const params   = {
        target_note_id : targetId,
        type,
      };
      const url      = nuxtApp.$config.public.apiUrl + '/tree' + '/' + id + '/' + 'move';
      const response = await nuxtApp.$axios.put(url, params);

      const node       = this.findTreeNode(this.getTree, id);
      const parentNode = this.findTreeNode(this.getTree, node.data.parent_note_id);
      this.deleteNodeExec({parentNode, id});
      if (parentNode.children.length === 0) {
        parentNode.data.hasChild = false;
      }

      const insertNode = node;
      this.setNodeData({ node : insertNode, data : response });
      const insertParentNode         = this.findTreeNode(this.getTree, insertNode.data.parent_note_id);
      insertParentNode.data.hasChild = true;

      switch (type) {
      case 'before':
        this.setBeforeTree({ parentNode : insertParentNode, beforeTargetId : targetId, insertNode });
        break;
      case 'after':
        this.setAfterTree({ parentNode : insertParentNode, afterTargetId : targetId, insertNode });
        break;
      case 'inside':
        this.setInsideTree({ parentNode : insertParentNode, insertNode });
        break;
      }
    },
    async addNode ({ data }) {
      const params = {
        title          : data.noteTitle,
        parent_note_id : data.noteId,
        note_type      : data.noteType,
      };
      const url    = nuxtApp.$config.public.apiUrl + '/notes';
      await nuxtApp.$axios
        .post(url, params)
        .then((response) => {
          const node       = convertNode(response);
          const parentNode = this.findTreeNode(this.getTree, node.data.parent_note_id);
          this.setInsideTree({ parentNode, insertNode : node });
          parentNode.data.hasChild = true;
        });
    },
    async updateNode ({ data, }) {
      const noteId = data.noteId;
      const params = {
        title : data.noteTitle,
      };
      const url    = nuxtApp.$config.public.apiUrl + '/notes' + '/' + noteId;
      await nuxtApp.$axios
        .put(url, params)
        .then((response) => {
          const node = this.findTreeNode(this.getTree, response.id);
          this.setNodeData({ node, data : response });
          node.title = response.title;
        });
    },
    async deleteNode (id = null) {
      id = id === null ? this.getSelectNoteId : id;

      const url      = nuxtApp.$config.public.apiUrl + '/notes' + '/' + id;
      const response = await nuxtApp.$axios
        .delete(url)
        .then((response) => {
          const node       = this.findTreeNode(this.getTree, id);
          const parentNode = this.findTreeNode(this.getTree, node.data.parent_note_id);
          this.deleteNodeExec({ parentNode, id });
          if (parentNode.children.length === 0) {
            parentNode.data.hasChild = false;
          }
          return response;
        });

      return response;
    },
    deleteNodeExec ({ parentNode, id }) {
      parentNode.children.forEach((node, index) => {
        if (node.data.id === id) {
          parentNode.children.splice(index, 1);
        }
      });
    },
    setNodeData ({ node, data, }) {
      node.data = Object.assign(node.data, data);
    },
    setBeforeTree ({ parentNode, beforeTargetId, insertNode, }) {
      let targetIndex = null;
      parentNode.children.forEach((node, index) => {
        if (node.data.id === beforeTargetId) {
          targetIndex = index;
        }
      });
      parentNode.children.splice(targetIndex, 0, insertNode);
    },
    setAfterTree ({ parentNode, afterTargetId, insertNode, }) {
      let targetIndex = null;
      parentNode.children.forEach((node, index) => {
        if (node.data.id === afterTargetId) {
          targetIndex = index;
        }
      });
      parentNode.children.splice(targetIndex + 1, 0, insertNode);
    },
    setInsideTree ({ parentNode, insertNode, }) {
      parentNode.children.push(insertNode);
    },
  }
});

function convertTree (treeNodes) {
  const ret = [];
  treeNodes.forEach((node) => {
    const data = convertNode(node);
    if (node.children.length !== 0) {
      data.children = convertTree(node.children);
    }
    ret.push(data);
  });
  return ret;
}

function convertNode (node) {
  return {
    title       : node.title,
    isLeaf      : false,
    $folded     : node.children.length === 0,
    children    : [],
    isSelected  : false,
    isDraggable : true,
    data        : node,
  };
}
