import object from '~/utils/object.js';
import sessionStorage from '~/utils/sessionStorage.js';
import localStorage from '~/utils/localStorage.js';

const util = {
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
