import router from './router'
import store from './store'
import Cookies from 'js-cookie'
import {message} from "./utils/resetMessage";

router.beforeEach(async (to, from, next) => {
  // 解决MVC跳转vue，没有config的情况
  const hasGetConfig = store.state.config
  if (!hasGetConfig) {
    try {
      await store.dispatch('getConfig')
    } catch (error) {
      console.log(error)
      message.error(error.message || 'Has Error')
      next(false)
    }
  }
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
