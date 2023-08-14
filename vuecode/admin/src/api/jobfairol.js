import request from '@/utils/request'
import apiArr from '@/api'
export function jobFairOnLineList(params) {
  return request({
    url: apiArr.jobFairListOl,
    method: 'get',
    params
  })
}
export function jobFairOnLineDelete(data) {
  return request({
    url: apiArr.jobFairListOlDelete,
    method: 'post',
    data
  })
}
export function jobFairOnLineAdd(data) {
  return request({
    url: apiArr.jobFairListOlAdd,
    method: 'post',
    data
  })
}
export function jobFairOnLineEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.jobFairListOlEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.jobFairListOlEdit,
      method,
      params
    })
  }
}
export function companyList(params) {
  return request({
    url: apiArr.jobFairListOlCompanyList,
    method: 'get',
    params
  })
}
export function personalList(params) {
  return request({
    url: apiArr.jobFairListOlPersonalList,
    method: 'get',
    params
  })
}
export function companySticky(data) {
  return request({
    url: apiArr.jobFairListOlSticky,
    method: 'post',
    data
  })
}
export function companyQrcode(data) {
  return request({
    url: apiArr.jobFairListOlQrcode,
    method: 'post',
    data
  })
}
export function participateAdd(params) {
  return request({
    url: apiArr.jobFairListOlParAdd,
    method: 'post',
    data: params
  })
}
export function companySearch(params) {
  return request({
    url: apiArr.jobFairListOlCompanySearch,
    method: 'get',
    params
  })
}
export function personalSearch(params) {
  return request({
    url: apiArr.jobFairListOlPersonalSearch,
    method: 'get',
    params
  })
}
export function participateStatus(data) {
  return request({
    url: apiArr.jobFairListOlStatus,
    method: 'post',
    data
  })
}
export function participateDelete(data) {
  return request({
    url: apiArr.jobFairListOlParticipateDelete,
    method: 'post',
    data
  })
}
export function setQrService(data) {
  return request({
    url: apiArr.jobFairListOlQrService,
    method: 'post',
    data
  })
}
export function companyBatchAdd(params) {
  return request({
    url: apiArr.jobFairListOlComBatchAdd,
    method: 'post',
    data: params
  })
}
export function personalBatchAdd(params) {
  return request({
    url: apiArr.jobFairListOlPerBatchAdd,
    method: 'post',
    data: params
  })
}
