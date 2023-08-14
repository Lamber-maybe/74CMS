import request from '@/utils/request'
import apiArr from '@/api'

export function serviceJobEmergencyList(params) {
  return request({
    url: apiArr.serviceJobEmergencyList,
    method: 'get',
    params
  })
}
export function serviceJobEmergencyAdd(data) {
  return request({
    url: apiArr.serviceJobEmergencyAdd,
    method: 'post',
    data
  })
}
export function serviceJobEmergencyEdit(data) {
  return request({
    url: apiArr.serviceJobEmergencyEdit,
    method: 'post',
    data
  })
}
export function serviceJobEmergencyDelete(data) {
  return request({
    url: apiArr.serviceJobEmergencyDelete,
    method: 'post',
    data
  })
}
