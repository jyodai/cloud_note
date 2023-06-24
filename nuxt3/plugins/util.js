import load from '~/utils/load.js';
import object from '~/utils/object.js';
import sessionStorage from '~/utils/sessionStorage.js';
import localStorage from '~/utils/localStorage.js';

const util = {
  load,
  object,
  sessionStorage,
  localStorage,
};

export default defineNuxtPlugin(() => {
  return {
    provide : {
      util,
    },
  };
});
