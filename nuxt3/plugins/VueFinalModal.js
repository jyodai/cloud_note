import { createVfm } from 'vue-final-modal';
import VueFinalModal from '~/libraries/vueFinalModal';

export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.vueApp.use(createVfm());
  const vfm                                   = nuxtApp.vueApp.config.globalProperties.$vfm;
  const wrapVfm                               = new VueFinalModal(vfm);
  nuxtApp.vueApp.config.globalProperties.$vfm = wrapVfm;
  nuxtApp.provide('vfm', wrapVfm);
});
