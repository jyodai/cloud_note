export default {
  set(key, value) {
    value = JSON.stringify(value);
    window.localStorage.setItem(key, value);
  },

  get(key) {
    const value = window.localStorage.getItem(key);
    return JSON.parse(value);
  },

  remove(key) {
    window.localStorage.removeItem(key);
  },

  exists(key) {
    const value = this.get(key);
    return value ? true : false;
  }
}
