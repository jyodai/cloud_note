import { createStore } from "vuex";
import axios from '~/libraries/axiosCore';



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
