import { createStore } from "vuex";
const store = createStore({});

export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.use(store);
});
