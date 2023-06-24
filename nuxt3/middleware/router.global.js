export default defineNuxtRouteMiddleware(async (to) => {
  const app = useNuxtApp()
  if (to.name === 'login') {
    return
  }

  if (!app.$util.sessionStorage.exists('token')) {
    return navigateTo('/login')
  }

  await app.$store.dispatch('User/setUser');

  return
})
