import request from '@/utils/request'
import apiArr from '@/api'

export function jobList(params) {
  return request({
    url: apiArr.jobList,
    method: 'get',
    params
  })
}
export function jobEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.jobEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.jobEdit,
      method,
      params
    })
  }
}
export function jobDelete(data) {
  return request({
    url: apiArr.jobDelete,
    method: 'post',
    data
  })
}
export function jobAudit(data) {
  return request({
    url: apiArr.jobAudit,
    method: 'post',
    data
  })
}
export function jobRefresh(data) {
  return request({
    url: apiArr.jobRefresh,
    method: 'post',
    data
  })
}
