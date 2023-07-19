import { defineStore } from 'pinia';
import Note from '~/types/models/note';
import NoteContent from '~/types/models/noteContent';

interface State {
  selectContent: NoteContent | null,
}

const nuxtApp = useNuxtApp();

export const useNoteContentStore = defineStore({
  id    : 'noteContent',
  state : (): State => ({
    selectContent : null,
  }),
  getters : {
    getSelectNoteId  : state => state.selectContent ? state.selectContent.note_id : null,
    getSelectContent : state => state.selectContent,
  },
  actions : {
    async loadSelectContent (note: Note): Promise<void> {
      const noteId       = note.id;
      const url          = nuxtApp.$config.public.apiUrl + `/notes/${noteId}/content`;
      const response     = await nuxtApp.$axios.get(url);
      const noteContent  = response.data as NoteContent;
      this.selectContent = noteContent;
    },
    unsetSelectContent () {
      this.selectContent = null;
    },
    async updateSelectContent (data: NoteContent): Promise<void> {
      const selectContent = Object.assign({}, this.getSelectContent);
      if (data.content === selectContent.content) {
        return;
      }

      selectContent.content = data.content;
      this.selectContent    = selectContent;

      const url    = nuxtApp.$config.public.apiUrl + `/note_content/${data.id}`;
      const params = {
        content : data.content,
      };
      await nuxtApp.$axios.put(url, params)
        .catch(() => {
          alert('メモの保存の失敗しました');
        });
    },
  }
});
