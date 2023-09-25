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

interface CustomInternalAxiosRequestConfig extends InternalAxiosRequestConfig {
  loadingScreen?: boolean;
}

const loadingScreen = new LoadingScreen();

const instance: AxiosInstance = axios.create({});

instance.interceptors.request.use((config: CustomInternalAxiosRequestConfig) => {
  if (enableLoadingScreen(config)) {
    loadingScreen.show();
  }

  const token: string | null = sessionStorage.get('token');
  if (token) {
    config.headers["Authorization"] = `Bearer ${token}`;
  }

  return config;
});

instance.interceptors.response.use(
  (response: AxiosResponse) => {
    if (enableLoadingScreen(response.config)) {
      loadingScreen.hide();
    }
    return response.data;
  },
  (error: AxiosError<ErrorData>) => {
    const config = error.config as CustomInternalAxiosRequestConfig;
    if (enableLoadingScreen(config)) {
      loadingScreen.hide();
    }
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

function enableLoadingScreen (config: CustomInternalAxiosRequestConfig) {
  if (!('loadingScreen' in config)) {
    return true;
  }
  return config.loadingScreen;
}

export default instance;

