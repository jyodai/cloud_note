import { defineStore } from 'pinia';
import Note from '~/types/models/note';
import User from '~/types/models/user';

const nuxtApp = useNuxtApp();

interface State {
  noteTab: Note[],
  selectNote : Note | null,
}

export const useNoteTabStore = defineStore({
  id    : 'noteTab',
  state : (): State => ({
    noteTab    : [], // ノートObjectを格納
    selectNote : null,
  }),
  getters : {
    getNoteTab      : state => state.noteTab,
    getSelectNote   : state => state.selectNote,
    getSelectNoteId : state=> state.selectNote ? state.selectNote.id : null,
    getNextNote     : state => {
      if (state.selectNote === null) {
        return null;
      }
      const selectNoteId = state.selectNote.id;
      const index        = state.noteTab.findIndex(note => note.id === selectNoteId);
      if (state.noteTab[index + 1]) {
        return state.noteTab[index + 1];
      } else if (state.noteTab.length > 1) {
        return state.noteTab[0];
      } else {
        return null;
      }
    },
    getPrevNote : state => {
      if (state.selectNote === null) {
        return null;
      }
      const selectNoteId = state.selectNote.id;
      const index        = state.noteTab.findIndex(note => note.id === selectNoteId);
      if (state.noteTab[index - 1]) {
        return state.noteTab[index - 1];
      } else if (state.noteTab.length > 1) {
        return state.noteTab[state.noteTab.length - 1];
      } else {
        return null;
      }
    },
    findNote : state => (id: number) => {
      const noteTab = state.noteTab;
      const index   = noteTab.findIndex(value => value.id === id);
      if (index === -1) {
        return null;
      }
      return noteTab[index];
    },
  },
  actions : {
    setSelectNote (note: Note): void {
      this.selectNote = note;
    },
    setNextNote (): void {
      const note = this.getNextNote;
      if (note !== null) {
        this.selectNote = note;
      }
    },
    setPrevNote (): void {
      const note = this.getPrevNote;
      if (note !== null) {
        this.selectNote = note;
      }
    },
    unsetSelectNote (): void {
      this.selectNote = null;
    },
    initNoteTab (): void {
      this.noteTab = [];
    },
    loadNoteTab (user: User): void {
      if (this.noteTab.length !== 0) {
        return;
      }

      const noteTabArray = nuxtApp.$util.localStorage.get<Note[]>('noteTab');
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
    setNoteTab (note: Note): void {
      this.noteTab.push(note);

      this.saveLocalStorage();
    },
    updateNote (data : {noteId: number, noteTitle: string}): void {
      const note = this.findNote(data.noteId) as Note;
      note.title = data.noteTitle;

      this.saveLocalStorage();
    },
    removeNoteTab (id: number): void {
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
    moveNoteTab (noteTabArray: Note[]): void {
      this.noteTab = [];
      noteTabArray.forEach((note) => {
        this.noteTab.push(note);
      });

      this.saveLocalStorage();
    },
    saveLocalStorage (): void {
      const noteTab = this.getNoteTab;
      nuxtApp.$util.localStorage.set('noteTab', noteTab);
    },
  },
});
