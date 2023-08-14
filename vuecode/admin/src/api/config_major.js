import request from '@/utils/request'
import apiArr from '@/api'

export function apiList(params) {
  return request({
    url: apiArr.configMajor,
    method: 'get',
    params
  })
}
export function apiEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.configMajorEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.configMajorEdit,
      method,
      params
    })
  }
}
export function apiAdd(data) {
  return request({
    url: apiArr.configMajorAdd,
    method: 'post',
    data
  })
}
export function apiOptions(params) {
  return request({
    url: apiArr.configMajorOptions,
    method: 'GET',
    params
  })
}
export function apiDelete(data) {
  return request({
    url: apiArr.configMajorDelete,
    method: 'post',
    data
  })
}
export function apiTableSave(data) {
  return request({
    url: apiArr.configMajorTableSave,
    method: 'post',
    data
  })
}
