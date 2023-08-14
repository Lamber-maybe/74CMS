import request from '@/utils/request'
import apiArr from '@/api'

export function wechatShareList(params) {
  return request({
    url: apiArr.wechatShareList,
    method: 'get',
    params
  })
}
export function wechatShareEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.wechatShareEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.wechatShareEdit,
      method,
      params
    })
  }
}
