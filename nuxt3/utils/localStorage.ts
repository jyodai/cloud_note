export default {
  set<T>(key: string, value: T): void {
    const serializedValue = JSON.stringify(value);
    window.localStorage.setItem(key, serializedValue);
  },

  get<T>(key: string): T | null {
    const value = window.localStorage.getItem(key);
    if (value) {
      return JSON.parse(value) as T;
    }
    return null;
  },

  remove(key: string): void {
    window.localStorage.removeItem(key);
  },

  exists(key: string): boolean {
    return this.get(key) !== null;
  },
};
