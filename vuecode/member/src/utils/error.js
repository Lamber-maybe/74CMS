import router from '@/router'
import store from '@/store'
// import { Message } from 'element-ui'
import { message } from '@/utils/resetMessage'
export function handlerHttpError (res) {
  switch (res.code) {
    case 50001: // 非法token
      if (res.message) {
        message.error(res.message)
      }
      store.commit('setLoginState', {
        whether: false,
        utype: 0,
        token: '',
        mobile: '',
        userIminfo: {}
      })
      break
    case 50002: // token失效
      if (res.message) {
        message.error(res.message)
      }
      store.commit('setLoginState', {
        whether: false,
        utype: 0,
        token: '',
        mobile: '',
        userIminfo: {}
      })
      router.push('/login')
      break
    case 50003: // 请先填写企业资料
      if (res.message) {
        message.error(res.message)
      }
      router.push('/company/manage/baseinfo')
      break
    case 50004: // 企业未认证，强制认证
      if (res.message) {
        message.error(res.message)
      }
      router.push('/company/auth')
      break
    case 50005: // 请先完善简历
      if (res.message) {
        message.error(res.message)
      }
      router.push('/personal/resume')
      break
    case 50006: // 第三方登录时验证结果：未绑定
      router.push({ path: '/bind', query: res.data })
      break
    case 50007: // 请先注册简历（引导到简历注册页）
      if (res.message) {
        message.error(res.message)
      }
      {
        let url = '/personal/resume/add_resume_step1'
        router.push(url)
      }
      break
    case 50008: // 暂时关闭网站
      router.push('/error?message=' + res.data)
      break
    case 50009: // 需要登录
      if (res.message) {
        message.error(res.message)
      }
      store.commit('setLoginState', {
        whether: false,
        utype: 0,
        token: '',
        mobile: '',
        userIminfo: {}
      })
      router.push('/login')
      break
    case 50010: // 需要个人登录
      if (res.message) {
        message.error(res.message)
      }
      store.commit('setLoginState', {
        whether: false,
        utype: 0,
        token: '',
        mobile: '',
        userIminfo: {}
      })
      router.push('/login')
      break
    case 50011: // 需要企业登录
      if (res.message) {
        message.error(res.message)
      }
      store.commit('setLoginState', {
        whether: false,
        utype: 0,
        token: '',
        mobile: '',
        userIminfo: {}
      })
      router.push('/login/company')
      break
    case 60001: // 禁止访问
      router.push('/ipdeny')
      break
    default:
      if (res.message) {
        message.error(res.message)
      }
      break
  }
}
