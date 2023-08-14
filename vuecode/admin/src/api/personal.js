import request from '@/utils/request'
import apiArr from '@/api'

export function personal(data) {
  return request({
    url: apiArr.personalIndex,
    method: 'post',
    data
  })
}
export function personalAvatar(data) {
  return request({
    url: apiArr.personalAvatar,
    method: 'post',
    data
  })
}
export function personalUsername(data) {
  return request({
    url: apiArr.personalUsername,
    method: 'post',
    data
  })
}
export function personalPassword(data) {
  return request({
    url: apiArr.personalPassword,
    method: 'post',
    data
  })
}
export function personalBindingCode(data) {
  return request({
    url: apiArr.personalBindingCode,
    method: 'post',
    data
  })
}
export function personalBindingMobile(data) {
  return request({
    url: apiArr.personalBindingMobile,
    method: 'post',
    data
  })
}
export function customerServiceMod(params) {
  return request({
    url: apiArr.customerCrmServiceEdit,
    method: 'get',
    params
  })
}
export function customerServiceEdit(data) {
  return request({
    url: apiArr.customerCrmServiceEdit,
    method: 'post',
    data
  })
}

