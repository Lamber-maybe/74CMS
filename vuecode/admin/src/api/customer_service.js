import request from '@/utils/request'
import apiArr from '@/api'

export function customerServiceList(params) {
  return request({
    url: apiArr.customerServiceList,
    method: 'get',
    params
  })
}
export function customerServiceAdd(data) {
  return request({
    url: apiArr.customerServiceAdd,
    method: 'post',
    data
  })
}
export function customerServiceEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.customerServiceEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.customerServiceEdit,
      method,
      params
    })
  }
}
export function customerServiceDelete(data) {
  return request({
    url: apiArr.customerServiceDelete,
    method: 'post',
    data
  })
}
