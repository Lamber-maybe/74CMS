import request from '@/utils/request'
import apiArr from '@/api'

export function flinkList(params) {
  return request({
    url: apiArr.flinkList,
    method: 'get',
    params
  })
}
export function flinkAdd(data) {
  return request({
    url: apiArr.flinkAdd,
    method: 'post',
    data
  })
}
export function flinkEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.flinkEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.flinkEdit,
      method,
      params
    })
  }
}
export function flinkDelete(data) {
  return request({
    url: apiArr.flinkDelete,
    method: 'post',
    data
  })
}
