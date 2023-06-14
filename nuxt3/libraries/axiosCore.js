import axios from "axios";

const instance = axios.create({});

instance.interceptors.response.use((response) => {
  return response.data
})

export default instance;
