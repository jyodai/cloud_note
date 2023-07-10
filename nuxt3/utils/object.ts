export default {
  clone (object: object): object {
    return JSON.parse(JSON.stringify(object));
  },
};
