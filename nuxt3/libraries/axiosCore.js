import axios from "axios";
import sessionStorage from '~/utils/sessionStorage.js';
import LoadingScreen from '~/libraries/loadingScreen.js';

const loadingScreen = new LoadingScreen();

const instance = axios.create({});

instance.interceptors.request.use((config) => {
  loadingScreen.show();

  const token                     = sessionStorage.get('token');
  config.headers["Authorization"] = `Bearer ${token}`;
  return config;
});

instance.interceptors.response.use(
  (response) => {
    loadingScreen.hide();
    return response.data;
  },
  (error) => {
    loadingScreen.hide();
    return Promise.reject(error.response.data);
  },
);

export default instance;
