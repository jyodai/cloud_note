import { defineStore } from 'pinia';

const nuxtApp = useNuxtApp();

export const useNoteContentStore = defineStore({
  id    : 'noteContent',
  state : () => ({
    selectContent : null,
  }),
  getters : {
    getSelectNoteId  : state => state.selectContent ? state.selectContent.note_id : null,
    getSelectContent : state => state.selectContent,
  },
  actions : {
    async loadSelectContent (data) {
      const noteId       = data.noteId;
      const url          = nuxtApp.$config.public.apiUrl + `/notes/${noteId}/content`;
      const response     = await nuxtApp.$axios.get(url);
      this.selectContent = response;
    },
    unsetSelectContent () {
      this.selectContent = null;
    },
    async updateSelectContent (data) {
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
