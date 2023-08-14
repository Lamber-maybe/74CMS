import request from '@/utils/request'
import apiArr from '@/api'

export function apiList(params) {
  return request({
    url: apiArr.configJobcategory,
    method: 'get',
    params
  })
}
export function apiEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.configJobcategoryEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.configJobcategoryEdit,
      method,
      params
    })
  }
}
export function apiAdd(data) {
  return request({
    url: apiArr.configJobcategoryAdd,
    method: 'post',
    data
  })
}
export function apiOptions(params) {
  return request({
    url: apiArr.configJobcategoryOptions,
    method: 'GET',
    params
  })
}
export function apiDelete(data) {
  return request({
    url: apiArr.configJobcategoryDelete,
    method: 'post',
    data
  })
}
export function apiTableSave(data) {
  return request({
    url: apiArr.configJobcategoryTableSave,
    method: 'post',
    data
  })
}
