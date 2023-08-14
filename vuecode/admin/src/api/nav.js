import request from '@/utils/request'
import apiArr from '@/api'

export function navList(params) {
  return request({
    url: apiArr.navList,
    method: 'get',
    params
  })
}
export function navAdd(data) {
  return request({
    url: apiArr.navAdd,
    method: 'post',
    data
  })
}
export function navEdit(params, method = 'post') {
  if (method == 'post') {
    return request({
      url: apiArr.navEdit,
      method,
      data: params
    })
  } else {
    return request({
      url: apiArr.navEdit,
      method,
      params
    })
  }
}
export function navDelete(data) {
  return request({
    url: apiArr.navDelete,
    method: 'post',
    data
  })
}

