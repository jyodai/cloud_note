import Util from '~/types/util';
import VueFinalModal from '~/libraries/vueFinalModal';
import { AxiosInstance } from "axios";
import commonConst from '~/const/common';

declare module '#app' {
  interface NuxtApp {
    $axios : AxiosInstance;
    $const : commonConst;
    $util : Util;
    $vfm: VueFinalModal;
  }
}

declare module 'vue' {
  interface ComponentCustomProperties {
    $axios : AxiosInstance;
    $const : commonConst;
    $util : Util;
    $vfm: VueFinalModal;
  }
}

export { };
