import object from '~/utils/object';
import json from '~/utils/json';
import sessionStorage from '~/utils/sessionStorage';
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
