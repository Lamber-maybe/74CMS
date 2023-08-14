import request from '@/utils/request'
import apiArr from '@/api'

export function complaintList(params) {
  return request({
    url: apiArr.complaintList,
    method: 'get',
    params
  })
}
export function complaintDelete(data) {
  return request({
    url: apiArr.complaintDelete,
    method: 'post',
    data
  })
}
export function complaintHandler(data) {
  return request({
    url: apiArr.complaintHandler,
    method: 'post',
    data
  })
}
