import router from './router'
import store from './store'
import Cookies from 'js-cookie'

router.beforeEach(async (to, from, next) => {
  // 每次打开应用时检查是否有存储的登录信息
  let visitor = Cookies.get('qscms_visitor')
  if(visitor===undefined || visitor===null || !visitor){
    store.commit('setLoginState', {
        whether: false,
        utype: 0,
        token: '',
        mobile: '',
        userIminfo: {}
      })
  }else{
    visitor = JSON.parse(visitor)
    store.commit('setLoginState', {
      whether: true,
      utype: visitor.utype,
      token: visitor.token,
      mobile: visitor.mobile,
      userIminfo: visitor.userIminfo
    })
  }
  if (store.state.LoginOrNot) {
    if (to.name === 'MemberLogin' || to.name === 'Register' || to.name === 'FindPwd' || to.name === 'Appeal') {
      if (store.state.LoginType === 1) {
        next('/company')
      } else {
        next('/personal')
      }
    } else if (to.meta.loginCheck === true && to.meta.utype !== 0 && to.meta.utype !== store.state.LoginType) {
      if (store.state.LoginType === 1) {
        next('/company')
      } else {
        next('/personal')
      }
    } else {
      next()
    }
  } else {
    if (to.meta.loginCheck === undefined || to.meta.loginCheck === false) {
      next()
    } else {
      if (to.meta.utype === 1) {
        next(`/login/company?redirect=${to.path}`)
      } else {
        next(`/login?redirect=${to.path}`)
      }
    }
  }
})
