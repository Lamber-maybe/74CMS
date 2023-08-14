import request from '@/utils/request'
import apiArr from '@/api'

export function noticeList(params) {
  return request({
    url: apiArr.noticeList,
    method: 'get',
    params
  })
}
export function noticeAdd(data) {
  return request({
    url: apiArr.noticeAdd,
    method: 'post',
    data
  })
}
export function noticeEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.noticeEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.noticeEdit,
      method,
      params
    })
  }
}
export function noticeDelete(data) {
  return request({
    url: apiArr.noticeDelete,
    method: 'post',
    data
  })
}
