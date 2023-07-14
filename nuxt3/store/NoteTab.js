import { defineStore } from 'pinia';

const nuxtApp = useNuxtApp();

export const useNoteTabStore = defineStore({
  id    : 'noteTab',
  state : () => ({
    noteTab    : [], // ノートObjectを格納
    selectNote : null,
  }),
  getters : {
    getNoteTab      : state => state.noteTab,
    getSelectNote   : state => state.selectNote,
    getSelectNoteId : state=> state.selectNote ? state.selectNote.id : null,
    getNextNote     : state => {
      const index = state.noteTab.findIndex(note => note.id === state.selectNote.id);
      if (state.noteTab[index + 1]) {
        return state.noteTab[index + 1];
      } else if (state.noteTab.length > 1) {
        return state.noteTab[0];
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
  },
  actions : {
    setSelectNote (note) {
      this.selectNote = note;
    },
    setNextNote () {
      const note = this.getNextNote;
      if (note !== null) {
        this.selectNote = note;
      }
    },
    setPrevNote () {
      const note = this.getPrevNote;
      if (note !== null) {
        this.selectNote = note;
      }
    },
    unsetSelectNote () {
      this.selectNote = null;
    },
    initNoteTab () {
      this.noteTab = [];
    },
    loadNoteTab (user) {
      if (this.noteTab.length !== 0) {
        return;
      }

      const noteTabArray = nuxtApp.$util.localStorage.get('noteTab');
      if (noteTabArray) {
        noteTabArray.forEach((note) => {
          if (user.id === note.user_id) {
            this.noteTab.push(note);
          }
        });
        this.saveLocalStorage();
      } else {
        nuxtApp.$util.localStorage.set('noteTab', []);
      }
    },
    setNoteTab (note) {
      this.noteTab.push(note);

      this.saveLocalStorage();
    },
    updateNote ({ data }) {
      const note = this.findNote(data.noteId);
      note.title = data.noteTitle;

      this.saveLocalStorage();
    },
    removeNoteTab (id) {
      if (this.getSelectNoteId === id) {
        this.setNextNote();
      }

      const index = this.noteTab.findIndex(value => value.id === id);
      if (index !== -1) {
        this.noteTab.splice(index, 1);
      }

      const noteTab = this.getNoteTab;
      if (noteTab.length === 0) {
        this.unsetSelectNote();
      }

      this.saveLocalStorage();
    },
    moveNoteTab (noteTabArray) {
      this.noteTab = [];
      noteTabArray.forEach((note) => {
        this.noteTab.push(note);
      });

      this.saveLocalStorage();
    },
    saveLocalStorage () {
      const noteTab = this.getNoteTab;
      nuxtApp.$util.localStorage.set('noteTab', noteTab);
    },
  },
});
