import request from '@/utils/request'
import apiArr from '@/api'

export function hrtoolList(params) {
  return request({
    url: apiArr.hrtoolList,
    method: 'get',
    params
  })
}
export function hrtoolAdd(data) {
  return request({
    url: apiArr.hrtoolAdd,
    method: 'post',
    data
  })
}
export function hrtoolEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.hrtoolEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.hrtoolEdit,
      method,
      params
    })
  }
}
export function hrtoolDelete(data) {
  return request({
    url: apiArr.hrtoolDelete,
    method: 'post',
    data
  })
}
