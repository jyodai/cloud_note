export default async ({ app, store, }) => {
  await beforeEach(app, store)
}

async function beforeEach (app, store) {
  await app.router.beforeEach(async (to, from, next) => {
    if (to.name === 'Login') {
      next()
      return
    }

    if (await tokenCheck(app, store) === false) {
      next({
        path: '/login',
      })
      return
    }

    next()
  })
}

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
  const response = await app.$axios.$get(process.env.API_SERVER_URl + '/user' + queryStr)
  if (response.user) {
    store.commit('User/setUser', response.user)
  }

  return true
}
