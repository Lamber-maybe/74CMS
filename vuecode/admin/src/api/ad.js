import request from '@/utils/request'
import apiArr from '@/api'

export function adList(params) {
  return request({
    url: apiArr.adList,
    method: 'get',
    params
  })
}
export function adAdd(data) {
  return request({
    url: apiArr.adAdd,
    method: 'post',
    data
  })
}
export function adEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.adEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.adEdit,
      method,
      params
    })
  }
}
export function adDelete(data) {
  return request({
    url: apiArr.adDelete,
    method: 'post',
    data
  })
}
export function adInnerLinkOptions(params) {
  return request({
    url: apiArr.adInnerLinkOptions,
    method: 'get',
    params
  })
}
export function adState(data) {
  return request({
    url: apiArr.adState,
    method: 'post',
    data
  })
}
