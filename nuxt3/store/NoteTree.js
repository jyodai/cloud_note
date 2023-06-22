const state = () => ({
  selectNote : null,
  treeNodes  : [],
})

const mutations = {
  setSelectNote (state, note) {
    state.selectNote = note
  },
  setTree (state, treeNodes) {
    state.treeNodes = treeNodes
  },
  setChildrenTree (state, { parentTreeNode, addTreeNodes, }) {
    parentTreeNode.children = addTreeNodes
  },
  setFolded(state, {node, flag}) {
    node.$folded = flag
  },
  setHasChild (state, { node, flag, }) {
    node.data.hasChild = flag
  },
  setTitle (state, { node, title, }) {
    node.title = title
  },
  deleteNode (state, { parentNode, id, }) {
    parentNode.children.forEach((node, index) => {
      if (node.data.id === id) {
        parentNode.children.splice(index, 1)
      }
    })
  },
  setNodeData (state, { node, data, }) {
    node.data = Object.assign(node.data, data)
  },
  setBeforeTree (state, { parentNode, beforeTargetId, insertNode, }) {
    let targetIndex = null
    parentNode.children.forEach((node, index) => {
      if (node.data.id === beforeTargetId) {
        targetIndex = index
      }
    })
    parentNode.children.splice(targetIndex, 0, insertNode)
  },
  setAfterTree (state, { parentNode, afterTargetId, insertNode, }) {
    let targetIndex = null
    parentNode.children.forEach((node, index) => {
      if (node.data.id === afterTargetId) {
        targetIndex = index
      }
    })
    parentNode.children.splice(targetIndex + 1, 0, insertNode)
  },
  setInsideTree (state, { parentNode, insertNode, }) {
    parentNode.children.push(insertNode)
  },
}

const getters = {
  getSelectNoteId : state => state.selectNote === null ? null : state.selectNote.id,
  getTree         : state => state.treeNodes,
  getDisplayTree  : state => state.treeNodes[0] ? state.treeNodes[0].children : [],
  findTreeNode    : (state, getters) => (treeNodes, id) => {
    let ret = null
    treeNodes.forEach((node) => {
      if (ret !== null) {
        return
      }
      if (node.data.id === id) {
        ret = node
        return
      }
      if (node.children.length !== 0) {
        ret = getters.findTreeNode(node.children, id)
      }
    })
    return ret
  },
}

const actions = {
  setSelectTree ({ commit, }, note) {
    commit('setSelectNote', note)
  },
  async loadTree ({ commit, }) {
    const restoreTreeLists = localStorage.getItem('restoreTreeLists') === null
      ? JSON.stringify([])
      : localStorage.getItem('restoreTreeLists')

    const url = this.$config.public.apiUrl + '/tree';
    const params = {}
    if (restoreTreeLists.length !== 0) {
      params.tree = restoreTreeLists
    }
    const config = { params, }
    const response = await this.$axios.get(url, config)

    const rootNode = [
      {
        children : convertTree(response),
        data     : {
          id       : 0,
          hasChild : true,
        },
      },
    ]
    commit('setTree', rootNode)
  },
  async loadChildrenTree ({getters, commit}, id) {
    const url = this.$config.public.apiUrl + '/tree' + '/' + id + '/' + 'children'
    this.noteLoadFlag = true
    const response = await this.$axios.get(url)
    const addTreeNodes = convertTree(response)

    const parentTreeNode = getters.findTreeNode(getters.getTree, id)
    commit('setChildrenTree', { parentTreeNode, addTreeNodes, })
  },
  async openNode ({ getters, commit, dispatch, }, id) {
    const node = getters.findTreeNode(getters.getTree, id)
    const flag = false
    commit('setFolded', { node, flag, })

    await dispatch('loadChildrenTree', id)

    await dispatch('addRestoreTreeData', id)
  },
  async closeNode ({ getters, commit, dispatch, }, id) {
    const node = getters.findTreeNode(getters.getTree, id)
    const flag = true
    commit('setFolded', { node, flag, })

    const parentTreeNode = getters.findTreeNode(getters.getTree, id)
    commit('setChildrenTree', { parentTreeNode, addTreeNodes: [], })

    await dispatch('removeRestoreTreeData', id)
  },
  addRestoreTreeData (state, id) {
    let restore = JSON.parse(localStorage.getItem('restoreTreeLists'))
    if (restore === null) {
      restore = []
    }

    if (!restore.includes(id)) {
      restore.push(id)
    }

    localStorage.setItem('restoreTreeLists', JSON.stringify(restore))
  },
  removeRestoreTreeData ({ getters, }, id) {
    const restoreLists = JSON.parse(localStorage.getItem('restoreTreeLists'))
    const index = restoreLists.indexOf(id)
    restoreLists.splice(index, 1)

    // ネスト内の子ファイルが開いていた場合の対策
    const newRestoreLists = []
    restoreLists.forEach((treeId) => {
      if (getters.findTreeNode(getters.getTree, treeId) !== null) {
        newRestoreLists.push(treeId)
      }
    })

    localStorage.setItem('restoreTreeLists', JSON.stringify(newRestoreLists))
  },
  async moveNode ({ getters, commit, }, { id, position, }) {
    const targetId = position.node.data.id
    const type = position.placement
    const params = {
      target_note_id : targetId,
      type,
    }
    const url = this.$config.public.apiUrl + '/tree' + '/' + id + '/' + 'move'
    const response = await this.$axios.put(url, params)

    const node = getters.findTreeNode(getters.getTree, id)
    const parentNode = getters.findTreeNode(getters.getTree, node.data.parent_note_id)
    commit('deleteNode', { parentNode, id, })
    if (parentNode.children.length === 0) {
      commit('setHasChild', { node: parentNode, flag: false, })
    }

    const insertNode = node
    commit('setNodeData', { node: insertNode, data: response, })
    const insertParentNode = getters.findTreeNode(getters.getTree, insertNode.data.parent_note_id)
    commit('setHasChild', { node: insertParentNode, flag: true, })

    switch (type) {
      case 'before':
        commit('setBeforeTree', { parentNode: insertParentNode, beforeTargetId: targetId, insertNode, })
        break
      case 'after':
        commit('setAfterTree', { parentNode: insertParentNode, afterTargetId: targetId, insertNode, })
        break
      case 'inside':
        commit('setInsideTree', { parentNode: insertParentNode, insertNode, })
        break
    }
  },
  async addNode ({ getters, commit, }, { data, }) {
    const params = {
      noteTitle    : data.noteTitle,
      parentNoteId : data.noteId,
      noteType     : data.noteType,
    }
    const url = this.$config.public.apiUrl + '/notes'
    await this.$axios
      .post(url, params)
      .then((response) => {
        const node = convertNode(response)
        const parentNode = getters.findTreeNode(getters.getTree, node.data.parent_note_id)
        commit('setInsideTree', { parentNode, insertNode: node, })
        commit('setHasChild', { node: parentNode, flag: true, })
      })
  },
  async updateNode ({ getters, commit, }, { data, }) {
    const noteId = data.noteId
    const params = {
      noteTitle : data.noteTitle,
    }
    const url = this.$config.public.apiUrl + '/notes' + '/' + noteId
    await this.$axios
      .put(url, params)
      .then((response) => {
        const node = getters.findTreeNode(getters.getTree, response.id)
        commit('setNodeData', { node, data: response, })
        commit('setTitle', { node, title: response.title, })
      })
  },
  async deleteNode ({ getters, commit, }, id = null) {
    id = id === null ? getters.getSelectNoteId : id

    const url = this.$config.public.apiUrl + '/notes' + '/' + id
    const response = await this.$axios
      .delete(url)
      .then((response) => {
        const node = getters.findTreeNode(getters.getTree, id)
        const parentNode = getters.findTreeNode(getters.getTree, node.data.parent_note_id)
        commit('deleteNode', { parentNode, id, })
        if (parentNode.children.length === 0) {
          commit('setHasChild', { node: parentNode, flag: false, })
        }
        return response
      })

    return response
  },
}

function convertTree (treeNodes) {
  const ret = []
  treeNodes.forEach((node) => {
    const data = convertNode(node)
    if (node.children.length !== 0) {
      data.children = convertTree(node.children)
    }
    ret.push(data)
  })
  return ret
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
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  getters,
  actions,
}
