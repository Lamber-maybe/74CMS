import request from '@/utils/request'
import apiArr from '@/api'

export function smsBlacklist(params) {
  return request({
    url: apiArr.smsBlacklist,
    method: 'get',
    params
  })
}
export function smsBlacklistAdd(data) {
  return request({
    url: apiArr.smsBlacklistAdd,
    method: 'post',
    data
  })
}
export function smsBlacklistEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.smsBlacklistEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.smsBlacklistEdit,
      method,
      params
    })
  }
}
export function smsBlacklistDelete(data) {
  return request({
    url: apiArr.smsBlacklistDelete,
    method: 'post',
    data
  })
}
