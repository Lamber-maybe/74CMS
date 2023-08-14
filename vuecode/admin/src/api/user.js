import request from '@/utils/request'

export function login(data) {
  return request({
    url: '/login/index',
    method: 'post',
    data
  })
}

export function getInfo(token) {
  return request({
    url: '/login/userinfo',
    method: 'get',
    params: {
      token
    }
  })
}

export function logout() {
  return request({
    url: '/login/logout',
    method: 'post'
  })
}

export function getCaptcha() {
  return request({
    url: '/login/captcha',
    method: 'get'
  })
}
