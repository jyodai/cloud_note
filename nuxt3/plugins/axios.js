import axiosCore from "axios";

const axios = axiosCore.create({});

export default defineNuxtPlugin(() => {
  return {
      provide: {
        axios
      },
    };
});
