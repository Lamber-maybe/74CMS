import request from '@/utils/request'
import apiArr from '@/api'

export function apiList(params) {
  return request({
    url: apiArr.adminRoleList,
    method: 'get',
    params
  })
}
export function apiAdd(data) {
  return request({
    url: apiArr.adminRoleAdd,
    method: 'post',
    data
  })
}
export function apiEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.adminRoleEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.adminRoleEdit,
      method,
      params
    })
  }
}
export function apiDelete(data) {
  return request({
    url: apiArr.adminRoleDelete,
    method: 'post',
    data
  })
}
