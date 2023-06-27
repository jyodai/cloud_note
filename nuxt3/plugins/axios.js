import axios from '~/libraries/axiosCore.js';

export default defineNuxtPlugin(() => {
  return {
    provide : {
      axios
    },
  };
});
