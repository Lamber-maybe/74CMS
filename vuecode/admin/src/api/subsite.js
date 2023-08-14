import request from '@/utils/request'
import apiArr from '@/api'

export function subsiteList(params) {
  return request({
    url: apiArr.subsiteList,
    method: 'get',
    params
  })
}
export function subsiteAdd(data) {
  return request({
    url: apiArr.subsiteAdd,
    method: 'post',
    data
  })
}
export function subsiteEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.subsiteEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.subsiteEdit,
      method,
      params
    })
  }
}
export function subsiteDelete(data) {
  return request({
    url: apiArr.subsiteDelete,
    method: 'post',
    data
  })
}
export function subsiteIsDisplay(data) {
  return request({
    url: apiArr.subsiteIsDisplay,
    method: 'post',
    data
  })
}
