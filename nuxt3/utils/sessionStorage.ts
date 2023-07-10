export default {
  set(key: string, value: string): void {
    window.sessionStorage.setItem(key, value);
  },

  get(key: string): string | null {
    return window.sessionStorage.getItem(key);
  },

  remove(key: string): void {
    window.sessionStorage.removeItem(key);
  },

  exists(key: string): boolean {
    const value = this.get(key);
    return value ? true : false;
  }
};
