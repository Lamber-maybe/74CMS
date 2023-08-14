import request from '@/utils/request'
import apiArr from '@/api'

export function servicePointsList(params) {
  return request({
    url: apiArr.servicePointsList,
    method: 'get',
    params
  })
}
export function servicePointsAdd(data) {
  return request({
    url: apiArr.servicePointsAdd,
    method: 'post',
    data
  })
}
export function servicePointsEdit(data) {
  return request({
    url: apiArr.servicePointsEdit,
    method: 'post',
    data
  })
}
export function servicePointsDelete(data) {
  return request({
    url: apiArr.servicePointsDelete,
    method: 'post',
    data
  })
}
