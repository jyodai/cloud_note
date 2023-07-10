import axios from "axios";
import sessionStorage from '~/utils/sessionStorage';
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
    if (error.response.status === 422) {
      showValidateMessage(error);
    }
    return Promise.reject(error.response.data);
  },
);

function showValidateMessage(error) {
  let messages       = [];
  const responseData = error.response.data;
  for (const property in responseData.errors) {
    if (!Array.isArray(responseData.errors[property])) {
      continue;
    }
    messages = messages.concat(responseData.errors[property]);
  }
  alert(messages.join("\n"));
}

export default instance;
