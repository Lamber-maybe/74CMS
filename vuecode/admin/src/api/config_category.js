import request from '@/utils/request'
import apiArr from '@/api'

export function apiList(params) {
  return request({
    url: apiArr.configCategory,
    method: 'get',
    params
  })
}
export function apiAdd(data) {
  return request({
    url: apiArr.configCategoryAdd,
    method: 'post',
    data
  })
}
export function apiEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.configCategoryEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.configCategoryEdit,
      method,
      params
    })
  }
}
export function apiDelete(data) {
  return request({
    url: apiArr.configCategoryDelete,
    method: 'post',
    data
  })
}
export function apiTableSave(data) {
  return request({
    url: apiArr.configCategoryTableSave,
    method: 'post',
    data
  })
}
