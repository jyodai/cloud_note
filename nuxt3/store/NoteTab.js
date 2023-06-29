const state = () => ({
  noteTab    : [], // ノートObjectを格納
  selectNote : null,
});

const mutations = {
  setSelectNote (state, note) {
    state.selectNote = note;
  },
  unsetSelectNote (state) {
    state.selectNote = null;
  },
  setNoteTab (state, note) {
    state.noteTab.push(note);
  },
  updateNote (state, { note, data, }) {
    note.title = data.noteTitle;
  },
  removeNoteTab (state, noteId) {
    const index = state.noteTab.findIndex(value => value.id === noteId);
    if (index !== -1) {
      state.noteTab.splice(index, 1);
    }
  },
  unsetNoteTab (state) {
    state.noteTab = [];
  },
};

const getters = {
  getNoteTab      : state => state.noteTab,
  getSelectNote   : state => state.selectNote,
  getSelectNoteId : state => state.selectNote ? state.selectNote.id : null,
  getNextNote     : state => {
    const index = state.noteTab.findIndex(note => note.id === state.selectNote.id);
    if (state.noteTab[index + 1]) {
      return state.noteTab[index + 1];
    } else if (state.noteTab.length > 1) {
      return state.noteTab[1];
    } else {
      return null;
    }
  },
  getPrevNote : state => {
    const index = state.noteTab.findIndex(note => note.id === state.selectNote.id);
    if (state.noteTab[index - 1]) {
      return state.noteTab[index - 1];
    } else if (state.noteTab.length > 1) {
      return state.noteTab[state.noteTab.length - 1];
    } else {
      return null;
    }
  },
  findNote : state => (id) => {
    const noteTab = state.noteTab;
    const index   = noteTab.findIndex(value => value.id === id);
    if (index === -1) {
      return null;
    }
    return noteTab[index];
  },
};

const actions = {
  setSelectNote ({commit}, note) {
    commit('setSelectNote', note);
  },
  setNextNote ({getters, commit}) {
    const note = getters.getNextNote;
    if (note !== null) {
      commit('setSelectNote', note);
    }
  },
  setPrevNote ({getters, commit}) {
    const note = getters.getPrevNote;
    if (note !== null) {
      commit('setSelectNote', note);
    }
  },
  unsetSelectNote ({ commit, }) {
    commit('unsetSelectNote');
  },
  initNoteTab ({ commit, }) {
    commit('unsetNoteTab');
  },
  loadNoteTab ({ rootState, state, commit, dispatch, }) {
    if (state.noteTab.length !== 0) {
      return;
    }

    const noteTabArray = this.$util.localStorage.get('noteTab');
    if (noteTabArray) {
      noteTabArray.forEach((note) => {
        if (rootState.User.user.id === note.user_id) {
          commit('setNoteTab', note);
        }
      });
      dispatch('saveLocalStorage');
    } else {
      this.$util.localStorage.set('noteTab', []);
    }
  },
  setNoteTab ({ commit, dispatch, }, note) {
    commit('setNoteTab', note);

    dispatch('saveLocalStorage');
  },
  updateNote ({ commit, getters, dispatch, }, { data, }) {
    const note = getters.findNote(data.noteId);
    commit('updateNote', { note, data, });

    dispatch('saveLocalStorage');
  },
  removeNoteTab ({ commit, dispatch, }, id) {
    commit('removeNoteTab', id);

    dispatch('saveLocalStorage');
  },
  moveNoteTab ({ commit, dispatch, }, noteTabArray) {
    commit('unsetNoteTab');
    noteTabArray.forEach((note) => {
      commit('setNoteTab', note);
    });

    dispatch('saveLocalStorage');
  },
  saveLocalStorage ({ getters, }) {
    const noteTab = getters.getNoteTab;
    this.$util.localStorage.set('noteTab', noteTab);
  },
};

export default {
  namespaced : true,
  state,
  mutations,
  getters,
  actions,
};
