import request from '@/utils/request'
import apiArr from '@/api'

export function wechatKeywordList(params) {
  return request({
    url: apiArr.wechatKeywordList,
    method: 'get',
    params
  })
}
export function wechatKeywordAdd(data) {
  return request({
    url: apiArr.wechatKeywordAdd,
    method: 'post',
    data
  })
}
export function wechatKeywordEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.wechatKeywordEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.wechatKeywordEdit,
      method,
      params
    })
  }
}
export function wechatKeywordDelete(data) {
  return request({
    url: apiArr.wechatKeywordDelete,
    method: 'post',
    data
  })
}
