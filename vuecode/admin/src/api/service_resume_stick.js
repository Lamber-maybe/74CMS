import request from '@/utils/request'
import apiArr from '@/api'

export function serviceResumeStickList(params) {
  return request({
    url: apiArr.serviceResumeStickList,
    method: 'get',
    params
  })
}
export function serviceResumeStickAdd(data) {
  return request({
    url: apiArr.serviceResumeStickAdd,
    method: 'post',
    data
  })
}
export function serviceResumeStickEdit(data) {
  return request({
    url: apiArr.serviceResumeStickEdit,
    method: 'post',
    data
  })
}
export function serviceResumeStickDelete(data) {
  return request({
    url: apiArr.serviceResumeStickDelete,
    method: 'post',
    data
  })
}
