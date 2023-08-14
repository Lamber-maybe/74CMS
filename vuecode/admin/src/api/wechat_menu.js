import request from '@/utils/request'
import apiArr from '@/api'

export function wechatMenuList(params) {
  return request({
    url: apiArr.wechatMenuList,
    method: 'get',
    params
  })
}
export function wechatMenuAdd(data) {
  return request({
    url: apiArr.wechatMenuAdd,
    method: 'post',
    data
  })
}
export function wechatMenuEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.wechatMenuEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.wechatMenuEdit,
      method,
      params
    })
  }
}
export function wechatMenuDelete(data) {
  return request({
    url: apiArr.wechatMenuDelete,
    method: 'post',
    data
  })
}
export function wechatMenuSync(data) {
  return request({
    url: apiArr.wechatMenuSync,
    method: 'post',
    data
  })
}
