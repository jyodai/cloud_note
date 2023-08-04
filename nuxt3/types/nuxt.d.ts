import VueFinalModal from '~/libraries/vueFinalModal';

declare module '#app' {
  interface NuxtApp {
    $vfm: VueFinalModal;
  }
}

declare module 'vue' {
  interface ComponentCustomProperties {
    $vfm: VueFinalModal;
  }
}

export { };
