import request from '@/utils/request'
import apiArr from '@/api'

export function companySetmealList(params) {
  return request({
    url: apiArr.companySetmealList,
    method: 'get',
    params
  })
}
export function companySetmealLog(params) {
  return request({
    url: apiArr.companySetmealLog,
    method: 'get',
    params
  })
}
export function companySetmealEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.companySetmealEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.companySetmealEdit,
      method,
      params
    })
  }
}
export function companySetmealAdd(data) {
  return request({
    url: apiArr.companySetmealAdd,
    method: 'post',
    data
  })
}
