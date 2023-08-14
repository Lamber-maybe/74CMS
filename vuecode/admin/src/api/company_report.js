import request from '@/utils/request'
import apiArr from '@/api'

export function companyReportList(params) {
  return request({
    url: apiArr.companyReportList,
    method: 'get',
    params
  })
}
export function companyReportAdd(data) {
  return request({
    url: apiArr.companyReportAdd,
    method: 'post',
    data
  })
}
export function companyReportEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.companyReportEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.companyReportEdit,
      method,
      params
    })
  }
}
export function companyReportDelete(data) {
  return request({
    url: apiArr.companyReportDelete,
    method: 'post',
    data
  })
}
export function companyReportSearchCompany(params) {
  return request({
    url: apiArr.companyReportSearchCompany,
    method: 'get',
    params
  })
}
