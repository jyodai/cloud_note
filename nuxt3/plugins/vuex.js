import { createStore } from "vuex";
import User from '~/store/User.js';
import NoteTab from '~/store/NoteTab.js';
import NoteTree from '~/store/NoteTree.js';
import NoteContent from '~/store/NoteContent.js';

const store = createStore({
  modules: {
    User,
    NoteTab,
    NoteTree,
    NoteContent,
  },
});

export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.use(store);
});
