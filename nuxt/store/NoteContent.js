export const state = () => ({
  selectNote: null,
})

export const mutations = {
  setSelectNote (state, note) {
    state.selectNote = note
  },
  unsetSelectNote (state) {
    state.selectNote = null
  },
}

export const getters = {
  getSelectNoteId : state => state.selectNote ? state.selectNote.id : null,
  getSelectNote   : state => state.selectNote,
}

export const actions = {
  async loadSelectNote ({ rootState, commit, }, data) {
    const noteId = data.noteId
    const queryStr = '?token=' + rootState.User.token + '&type=content' + '&noteId=' + noteId
    const response = await this.$axios.$get(process.env.API_SERVER_URl + '/notes' + queryStr)
    await commit('setSelectNote', response)
  },
  unsetSelectNote ({ commit, }) {
    commit('unsetSelectNote')
  },
  async updateSelectNote ({ rootState, getters, commit, }, data) {
    const selectNote = Object.assign({}, getters.getSelectNote)
    if (data.content === selectNote.content) {
      return
    }

    selectNote.content = data.content
    commit('setSelectNote', selectNote)

    const params = new URLSearchParams()
    params.append('token', rootState.User.token)
    params.append('noteId', data.id)
    params.append('content', data.content)
    params.append('type', 'content')
    const config = {
      headers: {
        'X-HTTP-Method-Override' : 'PUT',
        'Content-Type'           : 'application/x-www-form-urlencoded',
      },
    }
    await this.$axios.$post(process.env.API_SERVER_URl + '/notes', params, config)
      .then((res) => {
      }).catch((e) => {
        alert('メモの保存の失敗しました')
      })
  },
}
