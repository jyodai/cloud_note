import axios, {
  AxiosInstance,
  InternalAxiosRequestConfig,
  AxiosResponse,
  AxiosError
} from "axios";
import sessionStorage from '~/utils/sessionStorage';
import LoadingScreen from '~/libraries/loadingScreen';

interface ErrorData {
  errors: {
    [property: string]: string[];
  }
}

const loadingScreen = new LoadingScreen();

const instance: AxiosInstance = axios.create({});

instance.interceptors.request.use((config: InternalAxiosRequestConfig) => {
  loadingScreen.show();

  const token: string | null = sessionStorage.get('token');
  if (token) {
    config.headers["Authorization"] = `Bearer ${token}`;
  }

  return config;
});

instance.interceptors.response.use(
  (response: AxiosResponse) => {
    loadingScreen.hide();
    return response.data;
  },
  (error: AxiosError<ErrorData>) => {
    loadingScreen.hide();
    if (error.response && error.response.status === 422) {
      showValidateMessage(error.response.data);
    }
    return Promise.reject(error.response && error.response.data);
  },
);

function showValidateMessage(data: ErrorData): void {

  let messages: string[] = [];

  for (const property in data.errors) {
    if (Array.isArray(data.errors[property])) {
      messages = messages.concat(data.errors[property]);
    }
  }
  alert(messages.join("\n"));
}

export default instance;

