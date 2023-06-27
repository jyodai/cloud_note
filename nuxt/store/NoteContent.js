export const state = () => ({
  selectContent: null,
})

export const mutations = {
  setSelectContent (state, note) {
    state.selectContent = note
  },
  unsetSelectContent (state) {
    state.selectContent = null
  },
}

export const getters = {
  getSelectNoteId  : state => state.selectContent ? state.selectContent.note_id : null,
  getSelectContent : state => state.selectContent,
}

export const actions = {
  async loadSelectContent ({ rootState, commit, }, data) {
    const noteId = data.noteId
    const queryStr = '?token=' + rootState.User.token + '&noteId=' + noteId
    const response = await this.$axios.$get(process.env.API_SERVER_URl + '/note_content' + queryStr)
    await commit('setSelectContent', response)
  },
  unsetSelectContent ({ commit, }) {
    commit('unsetSelectContent')
  },
  async updateSelectContent ({ rootState, getters, commit, }, data) {
    const selectContent = Object.assign({}, getters.getSelectContent)
    if (data.content === selectContent.content) {
      return
    }

    selectContent.content = data.content
    commit('setSelectContent', selectContent)

    const params = new URLSearchParams()
    params.append('token', rootState.User.token)
    params.append('noteId', data.id)
    params.append('content', data.content)
    const config = {
      headers: {
        'X-HTTP-Method-Override' : 'PUT',
        'Content-Type'           : 'application/x-www-form-urlencoded',
      },
    }
    await this.$axios.$post(process.env.API_SERVER_URl + '/note_content', params, config)
      .then((res) => {
      }).catch((e) => {
        alert('メモの保存の失敗しました')
      })
  },
}
