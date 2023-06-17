export default {
  clone (object) {
    return JSON.parse(JSON.stringify(object))
  },
}
