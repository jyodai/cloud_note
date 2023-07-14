export default defineNuxtRouteMiddleware(async (to) => {
  const app = useNuxtApp();
  if (to.name === 'login') {
    return;
  }

  if (!app.$util.sessionStorage.exists('token')) {
    return navigateTo('/login');
  }

  const user = await app.$axios.get(app.$config.public.apiUrl + '/users/me')
    .catch(() => { return; });
  if (!user) {
    return navigateTo('/login');
  }

  return;
});
