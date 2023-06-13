import Load from '~/utils/load.js';

const util = {
  load : Load,
};

export default defineNuxtPlugin(() => {
  return {
      provide: {
        util,
      },
    };
});
