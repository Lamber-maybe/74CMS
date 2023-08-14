import request from '@/utils/request'
import apiArr from '@/api'

export function serviceJobStickList(params) {
  return request({
    url: apiArr.serviceJobStickList,
    method: 'get',
    params
  })
}
export function serviceJobStickAdd(data) {
  return request({
    url: apiArr.serviceJobStickAdd,
    method: 'post',
    data
  })
}
export function serviceJobStickEdit(data) {
  return request({
    url: apiArr.serviceJobStickEdit,
    method: 'post',
    data
  })
}
export function serviceJobStickDelete(data) {
  return request({
    url: apiArr.serviceJobStickDelete,
    method: 'post',
    data
  })
}
