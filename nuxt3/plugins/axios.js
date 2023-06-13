import axiosCore from "axios";

const axios = axiosCore.create({});

axios.interceptors.response.use((response) => {
  return response.data
})

export default defineNuxtPlugin(() => {
  return {
      provide: {
        axios
      },
    };
});
