import request from '@/utils/request'
import apiArr from '@/api'

export function apiList(params) {
  return request({
    url: apiArr.adminList,
    method: 'get',
    params
  })
}
export function apiAdd(data) {
  return request({
    url: apiArr.adminAdd,
    method: 'post',
    data
  })
}
export function apiEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.adminEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.adminEdit,
      method,
      params
    })
  }
}
export function apiDelete(data) {
  return request({
    url: apiArr.adminDelete,
    method: 'post',
    data
  })
}
export function apiRoleOptions(params) {
  return request({
    url: apiArr.adminRoleOptions,
    method: 'get',
    params
  })
}
export function adminLogList(params) {
  return request({
    url: apiArr.adminLogList,
    method: 'get',
    params
  })
}
export function adminAllList(params) {
  return request({
    url: apiArr.adminAllList,
    method: 'get',
    params
  })
}
export function adminBindQrcode(params) {
  return request({
    url: apiArr.adminBindQrcode,
    method: 'get',
    params
  })
}
export function adminBindQrcodeCancel(data) {
  return request({
    url: apiArr.adminBindQrcodeCancel,
    method: 'post',
    data
  })
}
export function adminIsBindWechat(data) {
  return request({
    url: apiArr.adminIsBindWechat,
    method: 'post',
    data
  })
}
export function getAdminCrmData(data) {
  return request({
    url: apiArr.getAdminCrmData,
    method: 'post',
    data
  })
}
export function adminLock(data) {
  return request({
    url: apiArr.adminLock,
    method: 'post',
    data
  })
}
export function adminDeblocking(data) {
  return request({
    url: apiArr.adminDeblocking,
    method: 'post',
    data
  })
}
