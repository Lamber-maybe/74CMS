import request from '@/utils/request'
import apiArr from '@/api'

export function hotwordList(params) {
  return request({
    url: apiArr.hotwordList,
    method: 'get',
    params
  })
}
export function hotwordAdd(data) {
  return request({
    url: apiArr.hotwordAdd,
    method: 'post',
    data
  })
}
export function hotwordEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.hotwordEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.hotwordEdit,
      method,
      params
    })
  }
}
export function hotwordDelete(data) {
  return request({
    url: apiArr.hotwordDelete,
    method: 'post',
    data
  })
}
export function hotwordSaveAll(data) {
  return request({
    url: apiArr.hotwordSaveAll,
    method: 'post',
    data
  })
}
