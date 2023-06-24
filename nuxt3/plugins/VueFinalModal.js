import { createVfm } from 'vue-final-modal';
import VueFinalModal from '~/libraries/vueFinalModal.js';

export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.use(createVfm());
  const vfm                                   = nuxtApp.vueApp.config.globalProperties.$vfm;
  const instance                              = new VueFinalModal(vfm);
  nuxtApp.vueApp.config.globalProperties.$vfm = instance;
});
