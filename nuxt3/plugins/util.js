import load from '~/utils/load.js';
import object from '~/utils/object.js';
import sessionStorage from '~/utils/sessionStorage.js';

const util = {
  load,
  object,
  sessionStorage,
};

export default defineNuxtPlugin(() => {
  return {
      provide: {
        util,
      },
    };
});
