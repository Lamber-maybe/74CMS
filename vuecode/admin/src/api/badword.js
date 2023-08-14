import request from '@/utils/request'
import apiArr from '@/api'

export function badwordList(params) {
  return request({
    url: apiArr.badwordList,
    method: 'get',
    params
  })
}
export function badwordAdd(data) {
  return request({
    url: apiArr.badwordAdd,
    method: 'post',
    data
  })
}
export function badwordEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.badwordEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.badwordEdit,
      method,
      params
    })
  }
}
export function badwordDelete(data) {
  return request({
    url: apiArr.badwordDelete,
    method: 'post',
    data
  })
}
export function badwordImport(data) {
  return request({
    url: apiArr.badwordImport,
    method: 'post',
    data
  })
}
