import request from '@/utils/request'
import apiArr from '@/api'

export function helpList(params) {
  return request({
    url: apiArr.helpList,
    method: 'get',
    params
  })
}
export function helpAdd(data) {
  return request({
    url: apiArr.helpAdd,
    method: 'post',
    data
  })
}
export function helpEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.helpEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.helpEdit,
      method,
      params
    })
  }
}
export function helpDelete(data) {
  return request({
    url: apiArr.helpDelete,
    method: 'post',
    data
  })
}
