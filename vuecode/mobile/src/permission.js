import router from './router'
import store from './store'
import VueCookies from 'vue-cookies'

router.beforeEach(async (to, from, next) => {
  // 解决没有config的情况
  const hasGetConfig = store.state.config
  if (!hasGetConfig) {
    try {
      await store.dispatch('getConfig')
    } catch (error) {
      console.log(error)
      next(false)
    }
  }
  // 每次打开应用时检查是否有存储的登录信息
  let visitor = VueCookies.get('qscms_visitor')
  if (visitor === undefined || visitor === null || !visitor) {
    store.commit('setLoginState', {
      whether: false,
      utype: 0,
      token: '',
      mobile: '',
      userIminfo: {}
    })
  } else {
    store.commit('setLoginState', {
      whether: true,
      utype: visitor.utype,
      token: visitor.token,
      mobile: visitor.mobile,
      userIminfo: visitor.userIminfo
    })
  }

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
