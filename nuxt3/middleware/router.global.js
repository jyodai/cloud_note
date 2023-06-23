export default defineNuxtRouteMiddleware(async (to) => {
  const app = useNuxtApp()
  if (to.name === 'Login') {
    return
  }

  if (!app.$util.sessionStorage.exists('token')) {
    return navigateTo('/login')
  }

  await app.$store.dispatch('User/setUser');

  return
})
