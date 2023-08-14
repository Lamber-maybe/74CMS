import router from './router'

router.beforeEach(async (to, from, next) => {
  // 每次打开应用时检查是否有存储的登录信息
  let LoginOrNot = true
  let admintoken = localStorage.getItem('admintoken')
  if(admintoken ===undefined || admintoken ==='undefined' || admintoken === null || !admintoken ){
    LoginOrNot = false
  }
  if (LoginOrNot) {
    if (to.name === 'oauthWeixin' || to.name === 'login') {
      next('/')
    } else {
      next()
    }
  } else {
    if (to.meta.loginCheck === undefined || to.meta.loginCheck === false) {
      next()
    } else {
      next('/login')
    }
  }
})
