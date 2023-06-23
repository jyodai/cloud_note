export default defineNuxtRouteMiddleware(async (to) => {
  const app = useNuxtApp()
  if (to.name === 'Login') {
    return
  }

  if (tokenCheck() === false) {
    return navigateTo('/login')
  }

  await app.$store.dispatch('User/setUser');

  return
})

function tokenCheck () {
  if (!('sessionStorage' in window) || (window.sessionStorage === null)) {
    // ストレージ使用不可
    return false
  }

  const token = sessionStorage.getItem('token')
  if (!token) {
    return false
  }
  return true;
}
