import axios from "axios";
import sessionStorage from '~/utils/sessionStorage.js';

const instance = axios.create({});

instance.interceptors.request.use((config) => {
  const token = sessionStorage.get('token');
  config.headers["Authorization"] = `Bearer ${token}`;
  return config;
});

instance.interceptors.response.use(
  (response) => {
    return response.data
  },
  (error) => {
    return Promise.reject(error.response.data);
  },
)

export default instance;
