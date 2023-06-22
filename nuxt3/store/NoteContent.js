const state = () => ({
  selectContent: null,
})

const mutations = {
  setSelectContent (state, note) {
    state.selectContent = note
  },
  unsetSelectContent (state) {
    state.selectContent = null
  },
}

const getters = {
  getSelectNoteId  : state => state.selectContent ? state.selectContent.note_id : null,
  getSelectContent : state => state.selectContent,
}

const actions = {
  async loadSelectContent ({commit, }, data) {
    const noteId = data.noteId
    const url = this.$config.public.apiUrl + '/note_content?noteId=' + noteId
    const response = await this.$axios.get(url)
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

    const url = this.$config.public.apiUrl + '/note_content';
    const params = {
      noteId : data.id,
      content : data.content,
    };
    await this.$axios.put(url, params)
      .then((res) => {
      }).catch((e) => {
        alert('メモの保存の失敗しました')
      })
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  getters,
  actions,
}
