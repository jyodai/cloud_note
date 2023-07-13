import { createStore } from "vuex";
import axios from '~/libraries/axiosCore';
import User from '~/store/User.js';
import NoteTab from '~/store/NoteTab.js';
import NoteTree from '~/store/NoteTree.js';



export default defineNuxtPlugin((nuxtApp) => {
  const registerAxios = (store) => {
    store.$axios = axios;
  };

  const runtimeConfig  = useRuntimeConfig();
  const registerConfig = (store) => {
    store.$config = runtimeConfig;
  };

  const registerConst = (store) => {
    store.$const = nuxtApp.$const;
  };

  const registerUtil = (store) => {
    store.$util = nuxtApp.$util;
  };

  const store = createStore({
    modules : {
      User,
      NoteTab,
      NoteTree,
    },
    plugins : [
      registerAxios,
      registerConfig,
      registerConst,
      registerUtil,
    ]
  });

  return {
    provide : {
      store,
    },
  };
});
