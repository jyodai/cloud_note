import note from '~/utils/note';
import object from '~/utils/object';
import json from '~/utils/json';
import sessionStorage from '~/utils/sessionStorage';
import localStorage from '~/utils/localStorage';

const util = {
  note,
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
