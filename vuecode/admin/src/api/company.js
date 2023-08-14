import request from '@/utils/request'
import apiArr from '@/api'

export function companyList(params) {
  return request({
    url: apiArr.companyList,
    method: 'get',
    params
  })
}
export function companyAdd(data) {
  return request({
    url: apiArr.companyAdd,
    method: 'post',
    data
  })
}
export function companyEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.companyEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.companyEdit,
      method,
      params
    })
  }
}
export function companyAudit(data) {
  return request({
    url: apiArr.companyAudit,
    method: 'post',
    data
  })
}
export function companyImgList(params) {
  return request({
    url: apiArr.companyImgList,
    method: 'get',
    params
  })
}
export function companyImgAudit(data) {
  return request({
    url: apiArr.companyImgAudit,
    method: 'post',
    data
  })
}
export function companyImgDelete(data) {
  return request({
    url: apiArr.companyImgDelete,
    method: 'post',
    data
  })
}
export function companyInterviewList(params) {
  return request({
    url: apiArr.companyInterviewList,
    method: 'get',
    params
  })
}
export function companyInterviewVideoList(params) {
  return request({
    url: apiArr.companyInterviewVideoList,
    method: 'get',
    params
  })
}
export function companyDownList(params) {
  return request({
    url: apiArr.companyDownList,
    method: 'get',
    params
  })
}
export function companyDelete(data) {
  return request({
    url: apiArr.companyDelete,
    method: 'post',
    data
  })
}
export function companySetService(data) {
  return request({
    url: apiArr.companySetService,
    method: 'post',
    data
  })
}
export function companySetDisplay(data) {
  return request({
    url: apiArr.companySetDisplay,
    method: 'post',
    data
  })
}
