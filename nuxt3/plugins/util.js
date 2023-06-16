import load from '~/utils/load.js';
import object from '~/utils/object.js';

const util = {
  load,
  object
};

export default defineNuxtPlugin(() => {
  return {
      provide: {
        util,
      },
    };
});
