import request from '@/utils/request'
import apiArr from '@/api'

export function couponList(params) {
  return request({
    url: apiArr.couponList,
    method: 'get',
    params
  })
}
export function couponAdd(data) {
  return request({
    url: apiArr.couponAdd,
    method: 'post',
    data
  })
}
export function couponEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.couponEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.couponEdit,
      method,
      params
    })
  }
}
export function couponDelete(data) {
  return request({
    url: apiArr.couponDelete,
    method: 'post',
    data
  })
}
export function couponLog(params) {
  return request({
    url: apiArr.couponLog,
    method: 'get',
    params
  })
}
export function couponRecord(params) {
  return request({
    url: apiArr.couponRecord,
    method: 'get',
    params
  })
}
export function couponSend(data) {
  return request({
    url: apiArr.couponSend,
    method: 'post',
    data
  })
}
