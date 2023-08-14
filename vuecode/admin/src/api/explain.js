import request from '@/utils/request'
import apiArr from '@/api'

export function explainList(params) {
  return request({
    url: apiArr.explainList,
    method: 'get',
    params
  })
}
export function explainAdd(data) {
  return request({
    url: apiArr.explainAdd,
    method: 'post',
    data
  })
}
export function explainEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.explainEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.explainEdit,
      method,
      params
    })
  }
}
export function explainDelete(data) {
  return request({
    url: apiArr.explainDelete,
    method: 'post',
    data
  })
}
