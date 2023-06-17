export default defineNuxtRouteMiddleware(async (to) => {
  const app = useNuxtApp()
  if (to.name === 'Login') {
    return
  }

  if (await tokenCheck(app, app.$store) === false) {
    return navigateTo('/login')
  }

  return
})

async function tokenCheck (app, store) {
  if (!('sessionStorage' in window) || (window.sessionStorage === null)) {
    // ストレージ使用不可
    return false
  }

  const token = sessionStorage.getItem('token')
  if (!token) {
    return false
  }

  store.commit('User/setToken', token)

  const queryStr = '?token=' + token
  const response = await app.$axios.get(app.$config.public.apiUrl + '/user' + queryStr)
  if (response.user) {
    store.commit('User/setUser', response.user)
  }

  return true
}
