import { defineStore } from 'pinia';
import Note from '~/types/models/note';

const nuxtApp = useNuxtApp();

interface TreeNode {
  title: string;
  isLeaf: boolean;
  $folded: boolean;
  children: TreeNode[];
  isSelected: boolean;
  isDraggable: boolean;
  data: Note;
}

interface State {
  selectNote : Note | null,
  treeNodes: TreeNode[],
}

export const useNoteTreeStore = defineStore({
  id    : 'noteTree',
  state : (): State => ({
    selectNote : null,
    treeNodes  : [],
  }),
  getters : {
    getSelectNoteId : (state): number | null => state.selectNote === null ? null : state.selectNote.id,
    getTree         : (state): TreeNode[] => state.treeNodes,
    getDisplayTree  : (state): TreeNode[] => state.treeNodes[0] ? state.treeNodes[0].children : [],
    findTreeNode    : (): ((treeNodes: TreeNode[], id: number) => TreeNode | null) => { 
      const findNode = (treeNodes: TreeNode[], id: number): TreeNode | null => {
        let ret: TreeNode | null = null;
        treeNodes.forEach((node) => {
          if (ret !== null) {
            return;
          }
          if (node.data.id === id) {
            ret = node;
            return;
          }
          if (node.children.length !== 0) {
            ret = findNode(node.children, id);
          }
        });
        return ret;
      };
      return findNode;
    },
  },
  actions : {
    setSelectTree (note: Note): void {
      this.selectNote = note;
    },
    async loadTree (): Promise<void> {
      const restoreTreeLists = nuxtApp.$util.localStorage.exists('restoreTreeLists')
        ? nuxtApp.$util.localStorage.get('restoreTreeLists')
        : [];

      const url      = nuxtApp.$config.public.apiUrl + '/tree';
      const params   = {
        tree : JSON.stringify(restoreTreeLists),
      };
      const config   = { params, };
      const response = await nuxtApp.$axios.get(url, config);
      const tree     = response.data as Note[];

      const rootNote = {
        id       : 0,
        hasChild : true,
      } as Note;

      const rootTreeNode = {
        children : convertTree(tree),
        data     : rootNote,
      } as TreeNode;

      this.treeNodes = [rootTreeNode];
    },
    async loadChildrenTree (id: number): Promise<void> {
      const url            = nuxtApp.$config.public.apiUrl + '/tree' + '/' + id + '/' + 'children';
      nuxtApp.noteLoadFlag = true;
      const response       = await nuxtApp.$axios.get(url);
      const tree           = response.data as Note[];
      const addTreeNodes   = convertTree(tree);

      const parentTreeNode    = this.findTreeNode(this.getTree, id) as TreeNode;
      parentTreeNode.children = addTreeNodes;
    },
    async openNode (id: number): Promise<void> {
      const node   = this.findTreeNode(this.getTree, id) as TreeNode;
      const flag   = false;
      node.$folded = flag;

      await this.loadChildrenTree(id);

      await this.addRestoreTreeData(id);
    },
    async closeNode (id: number): Promise<void> {
      const node   = this.findTreeNode(this.getTree, id) as TreeNode;
      const flag   = true;
      node.$folded = flag;

      const parentTreeNode    = this.findTreeNode(this.getTree, id) as TreeNode;
      parentTreeNode.children = [];

      await this.removeRestoreTreeData(id);
    },
    addRestoreTreeData (id: number): void {
      let restore = nuxtApp.$util.localStorage.get('restoreTreeLists') as Array<number>;
      if (restore === null) {
        restore = [];
      }

      if (!restore.includes(id)) {
        restore.push(id);
      }

      nuxtApp.$util.localStorage.set('restoreTreeLists', restore);
    },
    removeRestoreTreeData (id: number): void {
      const restoreLists = nuxtApp.$util.localStorage.get('restoreTreeLists') as Array<number>;
      const index        = restoreLists.indexOf(id);
      if (index === -1) {
        return;
      }
      restoreLists.splice(index, 1);

      // ネスト内の子ファイルが開いていた場合の対策
      const newRestoreLists:Array<number> = [];
      restoreLists.forEach((treeId) => {
        if (this.findTreeNode(this.getTree, treeId) !== null) {
          newRestoreLists.push(treeId);
        }
      });

      nuxtApp.$util.localStorage.set('restoreTreeLists', newRestoreLists);
    },
    async moveNode ({ id, position }: {id: number, position: { node : TreeNode, placement : string}}): Promise<void> {
      const targetId = position.node.data.id;
      const type     = position.placement;
      const params   = {
        target_note_id : targetId,
        type,
      };
      const url      = nuxtApp.$config.public.apiUrl + '/tree' + '/' + id + '/' + 'move';
      const response = await nuxtApp.$axios.put(url, params);
      const note     = response.data as Note;

      const node       = this.findTreeNode(this.getTree, id) as TreeNode;
      const parentNode = this.findTreeNode(this.getTree, node.data.parent_note_id) as TreeNode;
      this.deleteNodeExec(parentNode, id);
      if (parentNode.children.length === 0) {
        parentNode.data.hasChild = false;
      }

      const insertNode = node;
      this.setNodeData(insertNode, note);
      const insertParentNode         = this.findTreeNode(this.getTree, insertNode.data.parent_note_id) as TreeNode;
      insertParentNode.data.hasChild = true;

      switch (type) {
      case 'before':
        this.setBeforeTree(insertParentNode, targetId, insertNode);
        break;
      case 'after':
        this.setAfterTree(insertParentNode, targetId, insertNode);
        break;
      case 'inside':
        this.setInsideTree(insertParentNode, insertNode);
        break;
      }
    },
    async addNode ({ data }: { data: { noteTitle: string, noteId: number, noteType: string } }): Promise<void> {
      const params = {
        title          : data.noteTitle,
        parent_note_id : data.noteId,
        note_type      : data.noteType,
      };
      const url    = nuxtApp.$config.public.apiUrl + '/notes';
      await nuxtApp.$axios
        .post(url, params)
        .then((response) => {
          const note       = response.data as Note;
          const node       = convertNode(note);
          const parentNode = this.findTreeNode(this.getTree, node.data.parent_note_id) as TreeNode;
          this.setInsideTree(parentNode, node);
          parentNode.data.hasChild = true;
        });
    },
    async updateNode ({ data, }: { data: { noteId : number, noteTitle : string} }): Promise<void> {
      const noteId = data.noteId;
      const params = {
        title : data.noteTitle,
      };
      const url    = nuxtApp.$config.public.apiUrl + '/notes' + '/' + noteId;
      await nuxtApp.$axios
        .put(url, params)
        .then((response) => {
          const note = response.data as Note;
          const node = this.findTreeNode(this.getTree, note.id) as TreeNode;
          this.setNodeData(node, note);
          node.title = note.title;
        });
    },
    async deleteNode (id: number | null = null): Promise<Array<number>> {
      id = id === null ? this.getSelectNoteId : id;
      if (id === null) {
        throw new Error('ID is null');
      }

      const url        = nuxtApp.$config.public.apiUrl + '/notes' + '/' + id;
      const response   = await nuxtApp.$axios.delete(url);
      const deleteInfo = response.data as Array<number>;

      const node       = this.findTreeNode(this.getTree, id) as TreeNode;
      const parentNode = this.findTreeNode(this.getTree, node.data.parent_note_id) as TreeNode;
      this.deleteNodeExec(parentNode, id);
      if (parentNode.children.length === 0) {
        parentNode.data.hasChild = false;
      }

      return deleteInfo;
    },
    deleteNodeExec (parentNode: TreeNode, id: number): void {
      parentNode.children.forEach((node, index) => {
        if (node.data.id === id) {
          parentNode.children.splice(index, 1);
        }
      });
    },
    setNodeData (node: TreeNode, data: Note) {
      node.data = Object.assign(node.data, data);
    },
    setBeforeTree (parentNode: TreeNode, beforeTargetId: number, insertNode: TreeNode) {
      let targetIndex = null;
      parentNode.children.forEach((node, index) => {
        if (node.data.id === beforeTargetId) {
          targetIndex = index;
        }
      });
      if (targetIndex === null) {
        return;
      }
      parentNode.children.splice(targetIndex, 0, insertNode);
    },
    setAfterTree (parentNode: TreeNode, afterTargetId: number, insertNode: TreeNode) {
      let targetIndex = null;
      parentNode.children.forEach((node, index) => {
        if (node.data.id === afterTargetId) {
          targetIndex = index;
        }
      });
      if (targetIndex === null) {
        return;
      }
      parentNode.children.splice(targetIndex + 1, 0, insertNode);
    },
    setInsideTree (parentNode: TreeNode, insertNode: TreeNode) {
      parentNode.children.push(insertNode);
    },
  }
});

function convertTree (treeNodes: Note[]) {
  const ret: TreeNode[] = [];
  treeNodes.forEach((node) => {
    const data = convertNode(node);
    if (node.children.length !== 0) {
      data.children = convertTree(node.children);
    }
    ret.push(data);
  });
  return ret;
}

function convertNode (node: Note): TreeNode {
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
