import axios from "axios";

const instance = axios.create({});

instance.interceptors.request.use((config) => {
  const token = sessionStorage.getItem('token');
  config.headers["Authorization"] = `Bearer ${token}`;
  return config;
});

instance.interceptors.response.use((response) => {
  return response.data
})

export default instance;
