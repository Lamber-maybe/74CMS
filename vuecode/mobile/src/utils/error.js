import router from '@/router'
import store from '@/store'
import { Notify } from 'vant'
export function handlerHttpError (res) {
  switch (res.code) {
    case 50001: // 非法token
      if (res.message) {
        Notify(res.message)
      }
      break
    case 50002: // token失效
    case 50009: // 需要登录
      if (res.message) {
        Notify(res.message)
      }
      store.commit('setLoginState', {
        whether: false,
        utype: 0,
        token: '',
        mobile: '',
        userIminfo: {}
      })
      router.push('/member/login')
      break
    case 50003: // 请先填写企业资料
      if (res.message) {
        Notify(res.message)
      }
      router.push('/member/company/profile')
      break
    case 50004: // 企业未认证，强制认证
      if (res.message) {
        Notify(res.message)
      }
      router.push('/member/company/auth')
      break
    case 50005: // 请先完善简历
      if (res.message) {
        Notify(res.message)
      }
      router.push('/member/personal/resume')
      break
    case 50006: // 第三方登录时验证结果：未绑定
      router.push({ path: '/member/bind', query: res.data })
      break
    case 50007: // 请先注册简历（引导到简历注册页）
      if (res.message) {
        Notify(res.message)
      }
      let url =
        store.state.config.personal_reg_mode == 1
          ? '/member/personal/resume_add_form/step1'
          : '/member/personal/resume_add_int/step1'
      router.push(url)
      break
    case 50008: // 暂时关闭网站
      router.push('/error?message=' + res.data)
      break
    default:
      if (res.message) {
        Notify(res.message)
      }
      break
  }
}
