import request from '@/utils/request'
import apiArr from '@/api'

export function apiList(params) {
  return request({
    url: apiArr.configDistrict,
    method: 'get',
    params
  })
}
export function apiEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.configDistrictEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.configDistrictEdit,
      method,
      params
    })
  }
}
export function apiAdd(data) {
  return request({
    url: apiArr.configDistrictAdd,
    method: 'post',
    data
  })
}
export function apiOptions(params) {
  return request({
    url: apiArr.configDistrictOptions,
    method: 'GET',
    params
  })
}
export function apiDelete(data) {
  return request({
    url: apiArr.configDistrictDelete,
    method: 'post',
    data
  })
}
export function apiTableSave(data) {
  return request({
    url: apiArr.configDistrictTableSave,
    method: 'post',
    data
  })
}
