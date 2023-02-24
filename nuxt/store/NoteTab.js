export const state = () => ({
  noteTab: [], // ノートObjectを格納
})

export const mutations = {
  setNoteTab (state, note) {
    state.noteTab.push(note)
  },
  updateNote (state, { note, data, }) {
    note.title = data.noteTitle
  },
  removeNoteTab (state, noteId) {
    const index = state.noteTab.findIndex(value => value.id === noteId)
    if (index !== -1) {
      state.noteTab.splice(index, 1)
    }
  },
  unsetNoteTab (state) {
    state.noteTab = []
  },
}

export const getters = {
  getNoteTab      : state => state.noteTab,
  getSelectNoteId : state => state.selectNote ? state.selectNote.id : null,
  findNote        : state => (id) => {
    const noteTab = state.noteTab
    const index = noteTab.findIndex(value => value.id === id)
    if (index === -1) {
      return null
    }
    return noteTab[index]
  },
}

export const actions = {
  initNoteTab ({ commit, }) {
    commit('unsetNoteTab')
  },
  loadNoteTab ({ rootState, state, commit, dispatch, }) {
    if (state.noteTab.length !== 0) {
      return
    }

    const noteTabArray = JSON.parse(localStorage.getItem('noteTab'))
    if (noteTabArray) {
      noteTabArray.forEach((note) => {
        if (rootState.User.user.id === note.user_id) {
          commit('setNoteTab', note)
        }
      })
      dispatch('saveLocalStorage')
    } else {
      localStorage.setItem('noteTab', JSON.stringify([]))
    }
  },
  setNoteTab ({ commit, dispatch, }, note) {
    commit('setNoteTab', note)

    dispatch('saveLocalStorage')
  },
  updateNote ({ commit, getters, dispatch, }, { data, }) {
    const note = getters.findNote(data.noteId)
    commit('updateNote', { note, data, })

    dispatch('saveLocalStorage')
  },
  removeNoteTab ({ commit, dispatch, }, id) {
    commit('removeNoteTab', id)

    dispatch('saveLocalStorage')
  },
  moveNoteTab ({ commit, dispatch, }, noteTabArray) {
    commit('unsetNoteTab')
    noteTabArray.forEach((note) => {
      commit('setNoteTab', note)
    })

    dispatch('saveLocalStorage')
  },
  saveLocalStorage ({ getters, }) {
    const noteTab = getters.getNoteTab
    localStorage.setItem('noteTab', JSON.stringify(noteTab))
  },
}
