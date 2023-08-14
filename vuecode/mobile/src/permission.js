import router from './router'
import store from './store'

router.beforeEach(async (to, from, next) => {
  if (store.state.LoginOrNot) {
    if (to.name === 'MemberLogin' || to.name === 'MemberReg') {
      if (store.state.LoginType === 1) {
        next('/member/company/index')
      } else {
        next('/member/personal/index')
      }
    } else if (to.meta.loginCheck === true && to.meta.utype !== 0 && to.meta.utype !== store.state.LoginType) {
      if (store.state.LoginType === 1) {
        next('/member/company/index')
      } else {
        next('/member/personal/index')
      }
    } else {
      next()
    }
  } else {
    if (to.meta.loginCheck === undefined || to.meta.loginCheck === false) {
      next()
    } else {
      let realPath = to.path
      if (to.name == 'ImShow') {
        realPath = encodeURIComponent(to.fullPath)
      }
      if (to.meta.utype === 1) {
        next(`/member/login/company?redirect=${realPath}`)
      } else {
        next(`/member/login?redirect=${realPath}`)
      }
    }
  }
})
