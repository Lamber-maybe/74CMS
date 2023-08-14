import request from '@/utils/request'
import apiArr from '@/api'

export function orderList(params) {
  return request({
    url: apiArr.orderList,
    method: 'get',
    params
  })
}

export function orderConfirm(data) {
  return request({
    url: apiArr.orderConfirm,
    method: 'post',
    data
  })
}
export function orderCancel(data) {
  return request({
    url: apiArr.orderCancel,
    method: 'post',
    data
  })
}
