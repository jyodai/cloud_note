import axios from '~/libraries/axiosCore';

export default defineNuxtPlugin(() => {
  return {
    provide : {
      axios
    },
  };
});
