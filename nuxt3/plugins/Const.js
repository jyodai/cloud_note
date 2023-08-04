import commonConst from '~/const/common';

export default defineNuxtPlugin(() => {
  return {
    provide : {
      const : commonConst,
    },
  };
});
