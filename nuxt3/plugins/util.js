import object from '~/utils/object.js';
import json from '~/utils/json';
import sessionStorage from '~/utils/sessionStorage.js';
import localStorage from '~/utils/localStorage.js';

const util = {
  object,
  json,
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
