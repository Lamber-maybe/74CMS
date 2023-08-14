import request from '@/utils/request'
import apiArr from '@/api'

export function quickmsgList(data) {
  return request({
    url: apiArr.quickmsgList,
    method: 'post',
    data
  })
}
export function quickmsgAdd(data) {
  return request({
    url: apiArr.quickmsgAdd,
    method: 'post',
    data
  })
}
export function quickmsgEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.quickmsgEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.quickmsgEdit,
      method,
      params
    })
  }
}
export function quickmsgDelete(data) {
  return request({
    url: apiArr.quickmsgDelete,
    method: 'post',
    data
  })
}
